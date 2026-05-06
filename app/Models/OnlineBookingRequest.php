<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToBranch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OnlineBookingRequest extends Model
{
    use HasFactory, BelongsToBranch;

    protected $table = 'online_booking_requests';

    protected $fillable = ['branch_id', 'request_no', 'guest_name', 'phone', 'email', 'room_type_id', 'check_in_date', 'check_out_date', 'total_guests', 'deposit_amount', 'payment_method_id', 'payment_reference', 'status', 'approved_booking_id', 'note'];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    public function roomType(): BelongsTo { return $this->belongsTo(\App\Models\RoomType::class); }
    public function paymentMethod(): BelongsTo { return $this->belongsTo(\App\Models\PaymentMethod::class); }
    public function approvedBooking(): BelongsTo { return $this->belongsTo(\App\Models\Booking::class, 'approved_booking_id'); }
}
