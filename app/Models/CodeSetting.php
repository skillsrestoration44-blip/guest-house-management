<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeSetting extends Model
{
    use HasFactory;

    protected $table = 'code_settings';

    protected $fillable = ['code_type', 'prefix', 'next_number', 'digit_length', 'example'];

}
