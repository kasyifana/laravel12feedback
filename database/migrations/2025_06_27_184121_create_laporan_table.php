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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('judul', 255);
            $table->string('kategori', 100);
            $table->text('deskripsi');
            $table->enum('prioritas', ['Low', 'Medium', 'High']);
            $table->enum('status', ['Pending', 'In Progress', 'Selesai'])->default('Pending');
            $table->date('tanggal_lapor');
            $table->time('waktu_lapor');
            $table->string('nama_pelapor', 100)->nullable();
            $table->string('lampiran', 255)->nullable();
            $table->text('respon')->nullable();
            $table->string('oleh', 100)->nullable();
            $table->dateTime('waktu_respon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
