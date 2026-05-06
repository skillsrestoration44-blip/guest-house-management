<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditObserver
{
    /** Fields that must never be persisted into old/new values. */
    protected array $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    public function created(Model $model): void
    {
        $this->record($model, 'created', null, $this->scrub($model->getAttributes()));
    }

    public function updated(Model $model): void
    {
        $changed = $model->getChanges();
        if (empty($changed)) {
            return;
        }
        $original = [];
        foreach (array_keys($changed) as $k) {
            $original[$k] = $model->getOriginal($k);
        }
        $this->record($model, 'updated', $this->scrub($original), $this->scrub($changed));
    }

    public function deleted(Model $model): void
    {
        $this->record($model, 'deleted', $this->scrub($model->getAttributes()), null);
    }

    public function restored(Model $model): void
    {
        $this->record($model, 'restored', null, $this->scrub($model->getAttributes()));
    }

    protected function scrub(array $values): array
    {
        foreach ($this->hidden as $h) {
            if (array_key_exists($h, $values)) {
                $values[$h] = '***';
            }
        }
        return $values;
    }

    protected function record(Model $model, string $action, ?array $old, ?array $new): void
    {
        try {
            $request = function_exists('request') ? request() : null;
            AuditLog::create([
                'branch_id' => session('current_branch_id') ?? (Auth::user()?->branch_id),
                'user_id' => Auth::id(),
                'action' => $action,
                'module' => class_basename($model),
                'auditable_type' => $model::class,
                'auditable_id' => $model->getKey(),
                'old_values' => $old,
                'new_values' => $new,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
            ]);
        } catch (\Throwable $e) {
            /* Never let audit failures break the user-facing action. */
        }
    }
}
