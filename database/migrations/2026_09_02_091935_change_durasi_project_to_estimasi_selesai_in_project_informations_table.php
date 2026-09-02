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
        Schema::table('project_informations', function (Blueprint $table) {
            $table->dropColumn('durasi_project');
            $table->date('estimasi_selesai')->nullable()->after('estimasi_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_informations', function (Blueprint $table) {
            $table->integer('durasi_project')->nullable()->comment('bulan');
            $table->dropColumn('estimasi_selesai');
        });
    }
};
