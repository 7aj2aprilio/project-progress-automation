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
        Schema::create('project_cashflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->integer('month_index')->default(0);
            $table->date('month_date')->nullable();
            $table->decimal('pct_progress', 8, 4)->default(0);
            $table->decimal('pct_top_pelanggan', 8, 4)->default(0);
            $table->decimal('pct_top_mitra', 8, 4)->default(0);
            $table->decimal('jasa_konstruksi', 18, 2)->default(0);
            $table->decimal('management_fee', 18, 2)->default(0);
            $table->decimal('biaya_mitra', 18, 2)->default(0);
            $table->decimal('fee_jaminan', 18, 2)->default(0);
            $table->decimal('admin_jaminan', 18, 2)->default(0);
            $table->decimal('car', 18, 2)->default(0);
            $table->decimal('iuran_jasa', 18, 2)->default(0);
            $table->decimal('biaya_pengawasan', 18, 2)->default(0);
            $table->decimal('bop_project', 18, 2)->default(0);
            $table->decimal('cash_in', 18, 2)->default(0);
            $table->decimal('cash_out', 18, 2)->default(0);
            $table->decimal('gross_margin', 18, 2)->default(0);
            $table->decimal('pph', 18, 2)->default(0);
            $table->decimal('ppn_keluaran', 18, 2)->default(0);
            $table->decimal('ppn_masukan', 18, 2)->default(0);
            $table->decimal('kredit_ppn', 18, 2)->default(0);
            $table->decimal('penarikan_pinjaman', 18, 2)->default(0);
            $table->decimal('pembayaran_pokok', 18, 2)->default(0);
            $table->decimal('biaya_provisi', 18, 2)->default(0);
            $table->decimal('beban_bunga', 18, 2)->default(0);
            $table->decimal('cash_margin', 18, 2)->default(0);
            $table->decimal('cash_flow_kumulatif', 18, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_cashflows');
    }
};
