<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials = [$field => $login, 'password' => $request->input('password'), 'status' => 'active'];

        if (Auth::attempt($credentials, (bool) $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $user->forceFill(['last_login_at' => now()])->save();

            LoginHistory::create([
                'branch_id' => $user->branch_id,
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'status' => 'success',
            ]);

            sweetalert()->success(__('messages.login') . ' ' . __('messages.success'));

            return redirect()->intended(route('admin.dashboard'));
        }

        LoginHistory::create([
            'user_id' => User::where('username', $login)->orWhere('email', $login)->value('id'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
            'status' => 'failed',
            'failure_reason' => 'Invalid credentials',
        ]);

        return back()->withInput($request->only('login', 'remember'))
            ->withErrors(['login' => __('messages.something_went_wrong')]);
    }

    public function logout(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($userId) {
            LoginHistory::where('user_id', $userId)
                ->whereNull('logout_at')
                ->orderByDesc('id')
                ->limit(1)
                ->update(['logout_at' => now()]);
        }

        return redirect()->route('admin.login');
    }
}
