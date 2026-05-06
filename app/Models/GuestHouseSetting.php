<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestHouseSetting extends Model
{
    use HasFactory;

    protected $table = 'guest_house_settings';

    protected $fillable = ['name', 'logo', 'address', 'phone', 'email', 'website', 'tax_number', 'stamp_image', 'signature_image', 'currency', 'timezone'];

}
