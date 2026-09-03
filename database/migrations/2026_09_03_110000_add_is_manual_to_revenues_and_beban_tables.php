<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('project_revenues', 'is_manual')) {
            Schema::table('project_revenues', function (Blueprint $table) {
                $table->boolean('is_manual')->default(false)->after('amount');
            });
        }

        if (! Schema::hasColumn('project_beban', 'is_manual')) {
            Schema::table('project_beban', function (Blueprint $table) {
                $table->boolean('is_manual')->default(false)->after('amount');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('project_revenues', 'is_manual')) {
            Schema::table('project_revenues', function (Blueprint $table) {
                $table->dropColumn('is_manual');
            });
        }

        if (Schema::hasColumn('project_beban', 'is_manual')) {
            Schema::table('project_beban', function (Blueprint $table) {
                $table->dropColumn('is_manual');
            });
        }
    }
};
