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
        Schema::create('tabel_order', function (Blueprint $table) {
            $table->bigIncrements('id_order');
            $table->unsignedBigInteger('id_pel');
            $table->string('nomor_transaksi')->unique();
            $table->string('tipe_pembayaran'); // ex: 'cash', 'transfer'
            $table->integer('total_belanjaan');
            $table->string('status_order')->default('pending');  // ex: 'pending', 'success', 'failed'
            $table->string('bukti_pembayaran')->nullable(); // URL atau path ke bukti pembayaran
            $table->timestamps();
        
            $table->foreign('id_pel')->references('id_pel')->on('tabel_pelanggan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dataOrders', function (Blueprint $table) {
            //
        });
    }
};
