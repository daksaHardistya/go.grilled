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
        Schema::create('tabel_produk', function (Blueprint $table) {
            $table->bigIncrements('id_produk');
            $table->string('kode_produk')->unique();
            $table->string('nama_produk');
            $table->integer('harga_produk');
            $table->integer('stock_produk');
            $table->string('image_produk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_produk', function (Blueprint $table) {
            //
        });
    }
};
