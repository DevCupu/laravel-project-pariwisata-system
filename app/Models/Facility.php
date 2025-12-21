<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'capacity_per_day',
        'is_active',
        'is_locked',
        'sort_order',
        'bookable_type',
        'bookable_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
    ];

    public function bookable()
    {
        return $this->morphTo();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function refreshLockStatus(): void
    {
        $hasActiveBookings = $this->bookings()
            ->whereIn('status', Booking::ACTIVE_STATUSES)
            ->exists();

        if ($this->is_locked !== $hasActiveBookings) {
            $this->forceFill(['is_locked' => $hasActiveBookings])->save();
        }
    }
}
