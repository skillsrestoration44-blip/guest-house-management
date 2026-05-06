<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, BelongsToBranch;

    protected $table = 'users';

    protected $fillable = [
        'branch_id', 'staff_id', 'name', 'email', 'username', 'phone',
        'password', 'avatar', 'status', 'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function staff(): BelongsTo { return $this->belongsTo(Staff::class); }
    public function roles(): BelongsToMany { return $this->belongsToMany(Role::class, 'role_user'); }

    public function hasRole(string $name): bool
    {
        return $this->roles()->where('name', $name)->exists();
    }
}
