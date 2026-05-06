<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LocaleController extends Controller
{
    /**
     * AJAX endpoint to switch the active locale (without page refresh).
     *
     * Returns translated copies of the menu/labels so the front-end can
     * swap them in place via jQuery.
     */
    public function switch(Request $request): JsonResponse
    {
        $request->validate([
            'locale' => ['required', 'string', 'in:en,km'],
        ]);

        $locale = $request->string('locale')->toString();

        $request->session()->put('locale', $locale);
        App::setLocale($locale);

        cookie()->queue(cookie()->forever('locale', $locale));

        return response()->json([
            'status' => 'success',
            'locale' => $locale,
            'translations' => trans('messages'),
        ]);
    }
}
