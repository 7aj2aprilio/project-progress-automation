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
        Schema::create('gantt_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_item_id')->constrained()->cascadeOnDelete();
            $table->string('month_year'); // e.g. 2026-01
            $table->integer('week'); // e.g. 1, 2, 3, 4, 5
            $table->timestamps();
            
            // Unique constraint so a work item cannot be scheduled multiple times in the exact same week
            $table->unique(['project_id', 'work_item_id', 'month_year', 'week'], 'gantt_schedule_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gantt_schedules');
    }
};
