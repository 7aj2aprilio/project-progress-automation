<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cost_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->decimal('biaya_mitra_pelaksana', 20, 2)->nullable()->comment('exclude PPN');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cost_structures');
    }
};
