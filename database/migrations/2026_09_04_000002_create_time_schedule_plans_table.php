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
        Schema::create('time_schedule_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_week_id')->constrained('project_weeks')->cascadeOnDelete();
            $table->decimal('plan_value', 10, 4)->default(0);
            $table->timestamps();

            $table->unique(['project_id', 'work_item_id', 'project_week_id'], 'tsp_unique_item_week');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_schedule_plans');
    }
};
