<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['name', 'display_name', 'description', 'status'];

    public function users(): BelongsToMany { return $this->belongsToMany(\App\Models\User::class, 'role_user'); }
    public function permissions(): BelongsToMany { return $this->belongsToMany(\App\Models\Permission::class, 'permission_role'); }
}
