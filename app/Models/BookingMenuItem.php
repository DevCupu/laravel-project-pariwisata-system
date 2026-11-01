<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingMenuItem extends Model
{
    protected $fillable = [
        'menu_item_id',
        'menu_name',
        'quantity',
        'unit_price',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
