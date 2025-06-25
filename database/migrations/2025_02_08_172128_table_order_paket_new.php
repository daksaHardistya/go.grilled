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
        Schema::create('tabel_orderpaket', function (Blueprint $table) {
            $table->bigIncrements('id_orderPaket');
            $table->unsignedBigInteger('id_order');
            $table->unsignedBigInteger('id_paket');
            $table->integer('jumlah_orderPaket');
            $table->timestamps();
        
            $table->foreign('id_order')->references('id_order')->on('tabel_order')->onDelete('cascade');
            $table->foreign('id_paket')->references('id_paket')->on('tabel_paket')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_orderPaket', function (Blueprint $table) {
            //
        });
    }
};
