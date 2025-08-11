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
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rm', 20)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('nik', 20);
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->smallInteger('umur')->default(0);
            $table->text('alamat');
            $table->string('no_hp', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->enum('gol_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->enum('status_nikah', ['Belum Menikah', 'Menikah', 'Cerai'])->default('Belum Menikah');
            $table->string('pekerjaan', 50);
            $table->string('nama_kk', 100);
            $table->enum('hubungan_kk', ['Kepala Keluarga', 'Istri', 'Anak', 'Ayah', 'Ibu', 'Saudara', 'Keponakan', 'Cucu', 'Mertua', 'Menantu', 'Pembantu', 'Lainnya'])->nullable();
            $table->text('keluhan');
            $table->text('diagnosa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
