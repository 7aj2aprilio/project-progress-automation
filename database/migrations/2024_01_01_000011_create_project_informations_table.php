<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('nama_pelanggan')->nullable();
            $table->string('kategori_project')->nullable();
            $table->string('nama_project')->nullable();
            $table->string('lokasi_project')->nullable();
            $table->date('estimasi_mulai')->nullable();
            $table->integer('durasi_project')->nullable()->comment('bulan');
            $table->integer('durasi_retensi')->nullable()->comment('bulan');
            $table->enum('pengawasan_konstruksi', ['Sendiri', 'Menggunakan MK'])->nullable();
            $table->string('tipe_bangunan')->nullable();
            $table->string('top_pembayaran_mitra')->nullable();
            $table->string('top_pembayaran_pelanggan')->nullable();
            $table->decimal('total_revenue', 20, 2)->nullable();
            $table->decimal('loan_rate', 8, 4)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_informations');
    }
};
