<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::create('tabel_paket', function (Blueprint $table) {
            $table->bigIncrements('id_paket');
            $table->string('kode_paket')->unique();
            $table->string('nama_paket');
            $table->string('detail_paket');
            $table->string('kategori_paket');
            $table->integer('harga_paket');
            $table->integer('stock_paket');
            $table->string('image_paket');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_paket', function (Blueprint $table) {
            //
        });
    }
};
