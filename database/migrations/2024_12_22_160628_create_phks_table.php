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
  Schema::create('phks', function (Blueprint $table) {
   $table->id();
   $table->foreignId('id_karyawan')->constrained('users')->onDelete('cascade');
   $table->string('surat');
   $table->string('keterangan')->nullable();
   $table->string('status')->default('Menunggu');
   $table->timestamps();
  });
 }

 /**
  * Reverse the migrations.
  */
 public function down(): void
 {
  Schema::dropIfExists('phks');
 }
};
