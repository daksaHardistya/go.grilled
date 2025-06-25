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
        Schema::create('tabel_pelanggan', function (Blueprint $table) {
            $table->bigIncrements('id_pel');
            $table->string('nomor_tlp');
            $table->string('nama_pel');
            $table->text('alamat_pel');
            $table->string('email_pel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_pelanggan', function (Blueprint $table) {
            //
        });
    }
};
