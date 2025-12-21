<?php

namespace App\Models;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public const ACTIVE_STATUSES = ['pending', 'confirmed'];

    protected $fillable = [
        'booking_code',
        'name',
        'email',
        'phone',
        'visit_date',
        'guests',
        'package',
        'facility_id',
        'notes',
        'status',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
