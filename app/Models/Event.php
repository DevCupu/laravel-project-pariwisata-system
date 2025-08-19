<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class);
    }
}
