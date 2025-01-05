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
        Schema::create('promosi_demosis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_karyawan');
            $table->foreign('id_karyawan')->references('id')->on('users');
            $table->string('surat_rekomendasi');
            // $table->string('divisi_lama')->nullable();
            $table->unsignedBigInteger('divisi_lama_id');
            $table->foreign('divisi_lama_id')->references('id')->on('jabatans');
            // $table->string('divisi_baru');
            $table->unsignedBigInteger('divisi_baru_id');
            $table->foreign('divisi_baru_id')->references('id')->on('jabatans');
            $table->string('jenis');
            $table->string('status')->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promosi_demosis');
    }
};
