<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriWisata extends Model
{
    // 
    public function destinasi()
    {
        return $this->hasMany(Destinasi::class, 'kategori_id');
    }
}
