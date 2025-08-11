<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gudang_obat', function (Blueprint $table) {
            $table->foreignId('obat_id')->constrained('m_obat')->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained('m_gudang')->onDelete('cascade');
            $table->integer('stok')->default(0);
            $table->primary(['obat_id', 'gudang_id']);
        });

        Schema::create('emergency_kit_obat', function (Blueprint $table) {
            $table->foreignId('obat_id')->constrained('m_obat')->onDelete('cascade');
            $table->foreignId('emergency_kit_id')->constrained('emergency_kit')->onDelete('cascade');
            $table->integer('stok')->default(0);
            $table->primary(['obat_id', 'emergency_kit_id']);
            $table->timestamps();
        });

        Schema::create('obat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengambilan_obat_id')->constrained('pengambilan_obat')->onDelete('cascade');
            $table->foreignId('obat_id')->constrained('m_obat')->onDelete('cascade');
            $table->integer('jumlah');
            $table->string('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('obat_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained('m_obat')->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained('m_gudang')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('emergency_kit_id')->constrained('emergency_kit')->onDelete('cascade');
            $table->integer('jumlah');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('histori_gudang_obat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained('m_obat')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('gudang_id')->constrained('m_gudang')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('emergency_kit_id')->nullable()->constrained('emergency_kit')->onUpdate('cascade')->onDelete('set null');
            $table->integer('jumlah');
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gudang_obat');
        Schema::dropIfExists('emergency_kit_obat');
        Schema::dropIfExists('obat_keluar');
        Schema::dropIfExists('obat_masuk');
        Schema::dropIfExists('histori_gudang_obat');
    }
};