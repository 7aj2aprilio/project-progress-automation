<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->decimal('suku_bunga_pertahun', 8, 4)->nullable()->comment('percentage');
            $table->decimal('suku_bunga_perbulan', 8, 4)->nullable()->comment('auto = pertahun/12');
            $table->boolean('suku_bunga_perbulan_is_manual')->default(false);
            $table->decimal('pemodalan', 8, 4)->nullable()->comment('percentage');
            $table->decimal('provisi', 8, 4)->nullable()->comment('percentage');
            $table->decimal('ppn', 8, 4)->nullable()->comment('percentage');
            $table->decimal('pph_23', 8, 4)->nullable()->comment('percentage');
            $table->integer('periode_pinjaman')->nullable()->comment('bulan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assumptions');
    }
};
