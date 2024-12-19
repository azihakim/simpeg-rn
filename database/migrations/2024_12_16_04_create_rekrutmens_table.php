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
        Schema::create('rekrutmens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pelamar');
            $table->foreign('id_pelamar')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('id_lowongan');
            $table->foreign('id_lowongan')->references('id')->on('lowongans')->onDelete('cascade');
            $table->string('status')->default('Diajukan');
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekrutmens');
    }
};
