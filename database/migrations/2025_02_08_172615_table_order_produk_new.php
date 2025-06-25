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
        Schema::create('tabel_orderproduk', function (Blueprint $table) {
            $table->bigIncrements('id_orderProduk');
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_produk');
            $table->integer('jumlah_orderProduk');
            $table->timestamps();
        
            $table->foreign('id_order')->references('id_order')->on('tabel_order')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('tabel_produk')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_orderProduk', function (Blueprint $table) {
            //
        });
    }
};
