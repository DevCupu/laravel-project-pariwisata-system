<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{

    protected $fillable = [
        'nama',
        'deskripsi',
        'lokasi',
        'harga_tiket',
        'jam_buka',
        'jam_tutup',
        'status',
        'kategori_id',
        'gambar',
        'maps_embed',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriWisata::class, 'kategori_id');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function event()
    {
        return $this->hasMany(Event::class);
    }

    public function akomodasi()
    {
        return $this->hasMany(Akomodasi::class);
    }
}
