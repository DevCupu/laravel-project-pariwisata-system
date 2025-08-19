<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('destinasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi');
            $table->string('lokasi');
            $table->integer('harga_tiket')->default(0);
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->foreignId('kategori_id')->constrained('kategori_wisatas')->onDelete('cascade');
            $table->boolean('status')->default(true);
            $table->string('gambar')->nullable();
            $table->text('maps_embed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinasis');
    }
};
