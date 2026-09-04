<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                    <span class="p-2 rounded-lg bg-primary-100 text-primary-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </span>
                    Tentang & Panduan Penggunaan
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Panduan operasional dan dokumentasi fitur aplikasi Project Progress & Profitability Automation.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Versi {{ $appInfo['version'] }}
                </span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                    Laravel {{ $appInfo['laravel_version'] }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6" x-data="{ activeTab: 'overview' }">

        {{-- APP OVERVIEW CARD (Clean Corporate White Card) --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-5">
                <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-primary-50 text-primary-700 text-xs font-semibold mb-3 border border-primary-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ $appInfo['organization'] }}
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900">
                    {{ $appInfo['name'] }}
                </h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed mt-2 max-w-4xl">
                    Sistem terintegrasi yang dirancang untuk mendukung tim proyek dalam menganalisis profitabilitas dan kelayakan finansial (NPV, IRR, WACC, Margin Laba), simulasi arus kas bulanan (Cashflow), penyusunan Bill of Quantities (BoQ), penjadwalan Kurva S, hingga otomasi pembuatan Laporan Progres Mingguan resmi berstandar korporat.
                </p>
            </div>

            {{-- 4 FITUR UTAMA DALAM GRID BERSIH --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Analisis Profitabilitas</h4>
                        <p class="text-slate-500 text-xs mt-1 leading-normal">Kalkulasi otomatis kelayakan investasi (NPV, IRR, WACC, Payback Period, Net Margin).</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Simulasi Cashflow</h4>
                        <p class="text-slate-500 text-xs mt-1 leading-normal">Matriks arus kas bulanan berdasarkan termin TOP pelanggan, biaya mitra, dan pinjaman.</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">BoQ & Kurva S Jadwal</h4>
                        <p class="text-slate-500 text-xs mt-1 leading-normal">Pembobotan item pekerjaan otomatis dan matriks rencana jadwal mingguan (Gantt Chart).</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm">Laporan & Ekspor PDF</h4>
                        <p class="text-slate-500 text-xs mt-1 leading-normal">Laporan mingguan dengan deviasi, foto lapangan, dan cetak PDF resmi siap pakai.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB NAVIGATION (Clean Line Tabs) --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="border-b border-slate-200 px-4 sm:px-6">
                <nav class="flex space-x-1 sm:space-x-6 overflow-x-auto py-2" aria-label="Tabs">
                    <button @click="activeTab = 'overview'"
                            :class="activeTab === 'overview' ? 'border-primary-800 text-primary-900 font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'"
                            class="whitespace-nowrap py-3 px-2 text-sm flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Alur Kerja Utama
                    </button>

                    <button @click="activeTab = 'profitability'"
                            :class="activeTab === 'profitability' ? 'border-primary-800 text-primary-900 font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'"
                            class="whitespace-nowrap py-3 px-2 text-sm flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        1. Finansial & Cashflow
                    </button>

                    <button @click="activeTab = 'schedule'"
                            :class="activeTab === 'schedule' ? 'border-primary-800 text-primary-900 font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'"
                            class="whitespace-nowrap py-3 px-2 text-sm flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        2. BoQ, Jadwal & Kurva S
                    </button>

                    <button @click="activeTab = 'reports'"
                            :class="activeTab === 'reports' ? 'border-primary-800 text-primary-900 font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'"
                            class="whitespace-nowrap py-3 px-2 text-sm flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        3. Laporan Mingguan & PDF
                    </button>

                    <button @click="activeTab = 'roles'"
                            :class="activeTab === 'roles' ? 'border-primary-800 text-primary-900 font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'"
                            class="whitespace-nowrap py-3 px-2 text-sm flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        4. Hak Akses & Peran
                    </button>

                    <button @click="activeTab = 'faq'"
                            :class="activeTab === 'faq' ? 'border-primary-800 text-primary-900 font-bold border-b-2' : 'border-transparent text-slate-500 hover:text-slate-700 font-medium'"
                            class="whitespace-nowrap py-3 px-2 text-sm flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Formula & FAQ
                    </button>
                </nav>
            </div>

            {{-- TAB BODY --}}
            <div class="p-6 sm:p-8">

                {{-- ======================================================== --}}
                {{-- TAB 1: OVERVIEW ALUR KERJA --}}
                {{-- ======================================================== --}}
                <div x-show="activeTab === 'overview'" class="space-y-6">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900">Alur Kerja Proyek Dari Awal Hingga Selesai</h3>
                        <p class="text-sm text-slate-500 mt-1">Tahapan operasional pengelolaan proyek di dalam sistem secara berurutan:</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        {{-- Step 1 --}}
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="w-8 h-8 rounded-lg bg-primary-800 text-white font-bold text-sm flex items-center justify-center mb-3">1</div>
                                <h4 class="font-bold text-slate-900 text-sm">Buat Proyek & Asumsi</h4>
                                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">Input nama proyek, tanggal mulai & tanggal selesai (durasi bulan otomatis), customer, suku bunga pinjaman, diskonto, serta pajak standar.</p>
                            </div>
                            <div class="text-[11px] text-slate-500 border-t border-slate-200 pt-2.5">
                                Menu: <strong class="text-slate-800">Profitability dan Proyek</strong>
                            </div>
                        </div>

                        {{-- Step 2 --}}
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="w-8 h-8 rounded-lg bg-blue-700 text-white font-bold text-sm flex items-center justify-center mb-3">2</div>
                                <h4 class="font-bold text-slate-900 text-sm">Simulasi Cashflow</h4>
                                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">Isi termin pembayaran (TOP) pelanggan & mitra. Sistem otomatis menghitung NPV, IRR, Gross Margin, biaya provisi, dan bunga pinjaman bulanan.</p>
                            </div>
                            <div class="text-[11px] text-slate-500 border-t border-slate-200 pt-2.5">
                                Output: <strong class="text-slate-800">Ekspor PDF Cashflow</strong>
                            </div>
                        </div>

                        {{-- Step 3 --}}
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="w-8 h-8 rounded-lg bg-purple-700 text-white font-bold text-sm flex items-center justify-center mb-3">3</div>
                                <h4 class="font-bold text-slate-900 text-sm">BoQ & Matriks Jadwal</h4>
                                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">Input rincian item pekerjaan (BoQ) dengan harga satuan untuk mendapatkan bobot otomatis. Plot jadwal mingguan di matriks Gantt chart.</p>
                            </div>
                            <div class="text-[11px] text-slate-500 border-t border-slate-200 pt-2.5">
                                Menu: <strong class="text-slate-800">Progres & Laporan</strong>
                            </div>
                        </div>

                        {{-- Step 4 --}}
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="w-8 h-8 rounded-lg bg-emerald-700 text-white font-bold text-sm flex items-center justify-center mb-3">4</div>
                                <h4 class="font-bold text-slate-900 text-sm">Laporan Mingguan Resmi</h4>
                                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">Input realisasi fisik mingguan, pantau deviasi Kurva S, unggah foto dokumentasi visual lapangan, dan unduh PDF Laporan Mingguan resmi.</p>
                            </div>
                            <div class="text-[11px] text-slate-500 border-t border-slate-200 pt-2.5">
                                Output: <strong class="text-slate-800">PDF Laporan Mingguan</strong>
                            </div>
                        </div>
                    </div>

                    {{-- Sinkronisasi Info Box --}}
                    <div class="p-4 rounded-xl bg-primary-50 border border-primary-200 flex items-start gap-3.5">
                        <div class="p-2 rounded-lg bg-primary-800 text-white shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="text-xs text-primary-900 space-y-1">
                            <div class="font-bold text-sm">Sinkronisasi Otomatis Antar Modul:</div>
                            <p class="text-primary-800 leading-relaxed">
                                Saat Anda mengubah tanggal mulai dan selesai pada tab Informasi, sistem langsung menyesuaikan jumlah kolom bulan pada <strong>Cashflow Project</strong> serta timeline minggu pada <strong>Jadwal & Kurva S</strong>. Semua perhitungan pajak, provisi, dan bunga pinjaman dihitung otomatis tanpa perlu proses manual di luar aplikasi.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- TAB 2: FINANSIAL & CASHFLOW --}}
                {{-- ======================================================== --}}
                <div x-show="activeTab === 'profitability'" class="space-y-6">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900">Modul 1: Manajemen Profitabilitas & Cashflow</h3>
                        <p class="text-sm text-slate-500 mt-1">Panduan langkah teknis mengelola data finansial proyek hingga ekspor PDF.</p>
                    </div>

                    <div class="space-y-5">
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 flex items-start gap-4">
                            <span class="w-7 h-7 rounded-full bg-primary-800 text-white font-bold text-xs flex items-center justify-center shrink-0">1</span>
                            <div class="space-y-1 text-xs">
                                <h4 class="font-bold text-slate-900 text-sm">Membuat Proyek Baru</h4>
                                <p class="text-slate-600 leading-relaxed">
                                    Buka menu <strong>Profitability dan Proyek</strong> &rarr; Klik tombol <strong>"+ Buat Proyek Baru"</strong>. Masukkan nama proyek dan klik simpan. Sistem akan otomatis menyiapkan data pendukung (Informasi, Asumsi, Cost Structure, dan Cashflow).
                                </p>
                            </div>
                        </div>

                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 flex items-start gap-4">
                            <span class="w-7 h-7 rounded-full bg-primary-800 text-white font-bold text-xs flex items-center justify-center shrink-0">2</span>
                            <div class="space-y-2 text-xs flex-1">
                                <h4 class="font-bold text-slate-900 text-sm">Mengisi Tab "1. Informasi & Asumsi"</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div class="p-3 bg-white rounded-lg border border-slate-200">
                                        <strong class="text-slate-800 block mb-1">Periode Proyek:</strong>
                                        Isi Tanggal Mulai dan Tanggal Selesai. Durasi bulan akan terhitung otomatis dan menentukan jumlah kolom pada Cashflow.
                                    </div>
                                    <div class="p-3 bg-white rounded-lg border border-slate-200">
                                        <strong class="text-slate-800 block mb-1">Parameter Finansial:</strong>
                                        Masukkan Suku Bunga Pinjaman per Tahun, Discount Rate, serta tarif pajak (PPh 23 & PPN).
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 flex items-start gap-4">
                            <span class="w-7 h-7 rounded-full bg-primary-800 text-white font-bold text-xs flex items-center justify-center shrink-0">3</span>
                            <div class="space-y-2 text-xs flex-1">
                                <h4 class="font-bold text-slate-900 text-sm">Mengisi Tab "2. Cashflow Project"</h4>
                                <p class="text-slate-600 leading-relaxed">Isi nilai-nilai pada tabel arus kas bulanan:</p>
                                <ul class="list-disc list-inside space-y-1 text-slate-700 bg-white p-3.5 rounded-lg border border-slate-200">
                                    <li><strong>TOP Pelanggan (%):</strong> Termin penerimaan pembayaran dari klien (total kumulatif 100%).</li>
                                    <li><strong>TOP Mitra Pelaksana (%):</strong> Termin pengeluaran biaya mitra (total kumulatif 100%).</li>
                                    <li><strong>Jasa Pelaksanaan Konstruksi & Management Fee:</strong> Pendapatan per bulan.</li>
                                    <li><strong>Biaya Mitra Pelaksana:</strong> Biaya riil mitra pelaksana di lapangan.</li>
                                    <li><strong>Beban Lainnya:</strong> Fee/Admin Jaminan, CAR (Asuransi), Iuran Jasa, Biaya Pengawasan, dan BOP Project.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 flex items-start gap-4">
                            <span class="w-7 h-7 rounded-full bg-primary-800 text-white font-bold text-xs flex items-center justify-center shrink-0">4</span>
                            <div class="space-y-2 text-xs flex-1">
                                <h4 class="font-bold text-slate-900 text-sm">Membaca Rangkuman Kelayakan & Ekspor PDF</h4>
                                <p class="text-slate-600 leading-relaxed">
                                    Pada halaman Detail Proyek, indikator <strong>NPV, IRR, WACC, Gross Margin, Net Margin, dan Payback Period</strong> sudah terhitung secara otomatis. Klik tombol <strong>"Download PDF Cashflow"</strong> untuk mengunduh laporan PDF siap cetak dalam orientasi landscape.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- TAB 3: BOQ, JADWAL & KURVA S --}}
                {{-- ======================================================== --}}
                <div x-show="activeTab === 'schedule'" class="space-y-6">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900">Modul 2: Bill of Quantities (BoQ) & Penjadwalan Kurva S</h3>
                        <p class="text-sm text-slate-500 mt-1">Mengelola daftar pekerjaan, pembobotan otomatis, dan plotting jadwal mingguan.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                            <div class="w-9 h-9 rounded-lg bg-primary-800 text-white flex items-center justify-center font-bold text-sm">1</div>
                            <h4 class="font-bold text-slate-900 text-sm">Input Item BoQ</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Masuk ke menu <strong>Progres & Laporan</strong> &rarr; Pilih Proyek &rarr; Tab <strong>BoQ & Item Pekerjaan</strong>. Masukkan Uraian Pekerjaan, Volume, Satuan, dan Harga Satuan. <strong>Bobot (%)</strong> terhitung otomatis.
                            </p>
                        </div>

                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-700 text-white flex items-center justify-center font-bold text-sm">2</div>
                            <h4 class="font-bold text-slate-900 text-sm">Plotting Matriks Jadwal (Gantt)</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Buka tab <strong>Jadwal & Kurva S</strong>. Cukup klik pada kotak minggu (M1, M2, M3, M4) baris pekerjaan untuk mengaktifkan jadwal kerja. Bobot otomatis terbagi rata ke setiap minggu yang aktif.
                            </p>
                        </div>

                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-700 text-white flex items-center justify-center font-bold text-sm">3</div>
                            <h4 class="font-bold text-slate-900 text-sm">Membaca Kurva S & Cetak</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Grafik Kurva S menampilkan perbandingan <strong>Garis Rencana</strong> (Target kumulatif) vs <strong>Garis Realisasi</strong> (Progres aktual lapangan). Klik <strong>"Download PDF Jadwal"</strong> untuk mencetak.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- TAB 4: LAPORAN MINGGUAN & PDF --}}
                {{-- ======================================================== --}}
                <div x-show="activeTab === 'reports'" class="space-y-6">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900">Modul 3: Pelaporan Mingguan & Visual Dokumentasi</h3>
                        <p class="text-sm text-slate-500 mt-1">Pembuatan laporan mingguan resmi, upload foto lapangan, dan pembuatan berkas PDF.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                            <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-primary-800 text-white text-xs flex items-center justify-center">1</span>
                                Pengisian Progres Fisik Mingguan
                            </h4>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside leading-relaxed">
                                <li>Pilih Minggu Ke- dan tanggal periode laporan mingguan.</li>
                                <li>Input persentase realisasi mingguan pada tiap item pekerjaan BoQ.</li>
                                <li>Sistem menghitung <strong>Bobot Realisasi Kumulatif</strong> dan <strong>Deviasi</strong> (Realisasi - Rencana).</li>
                                <li>Isi catatan ringkasan progres, kendala lapangan, serta rekomendasi solusi.</li>
                            </ul>
                        </div>

                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                            <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-primary-800 text-white text-xs flex items-center justify-center">2</span>
                                Upload Foto & Download PDF
                            </h4>
                            <ul class="text-xs text-slate-600 space-y-1.5 list-disc list-inside leading-relaxed">
                                <li>Unggah foto dokumentasi visual pekerjaan beserta tanggal dan deskripsi aktivitas.</li>
                                <li>Pilih foto unggulan untuk dijadikan cover depan laporan.</li>
                                <li>Unduh berkas PDF resmi lengkap dengan Cover Laporan, Lembar Pengesahan, Rekapitulasi BoQ, Kurva S, dan Galeri Foto.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- TAB 5: HAK AKSES & PERAN PENGGUNA --}}
                {{-- ======================================================== --}}
                <div x-show="activeTab === 'roles'" class="space-y-6">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900">Modul 4: Hak Akses & Peran Pengguna (RBAC)</h3>
                        <p class="text-sm text-slate-500 mt-1">Tingkatan hak akses untuk menjamin keamanan dan akurasi data proyek.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-purple-100 text-purple-800 inline-block">Administrator</span>
                            <h4 class="font-bold text-slate-900 text-sm">Full Control</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Akses penuh ke semua modul proyek, pembuatan/pengaturan akun pengguna (Kelola User), dan konfigurasi Pengaturan Global sistem.</p>
                        </div>

                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-800 inline-block">Analis Proyek</span>
                            <h4 class="font-bold text-slate-900 text-sm">Operasional (CRUD)</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Dapat menambah dan mengedit proyek, mengisi cashflow, mengelola BoQ & jadwal Gantt, serta membuat Laporan Mingguan & ekspor PDF.</p>
                        </div>

                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                            <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-slate-200 text-slate-700 inline-block">Viewer</span>
                            <h4 class="font-bold text-slate-900 text-sm">Hanya Lihat (Read-Only)</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">Dikhususkan untuk pimpinan atau tim monitoring yang hanya memantau progres, melihat grafik Kurva S, dan mengunduh laporan PDF tanpa izin edit.</p>
                        </div>
                    </div>
                </div>

                {{-- ======================================================== --}}
                {{-- TAB 6: FORMULA & FAQ --}}
                {{-- ======================================================== --}}
                <div x-show="activeTab === 'faq'" class="space-y-6">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900">Formula Kalkulasi & Pertanyaan Umum (FAQ)</h3>
                        <p class="text-sm text-slate-500 mt-1">Daftar formula matematis sistem dan solusi kendala umum.</p>
                    </div>

                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-100 text-slate-700 uppercase font-semibold border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3">Komponen</th>
                                    <th class="px-4 py-3">Formula Default</th>
                                    <th class="px-4 py-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-slate-600">
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">Besar Pinjaman</td>
                                    <td class="px-4 py-3 font-mono bg-slate-50 text-slate-800">Cost Structure × 2%</td>
                                    <td class="px-4 py-3">Default 2% dari total biaya mitra (bisa di-override manual).</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">Biaya Provisi</td>
                                    <td class="px-4 py-3 font-mono bg-slate-50 text-slate-800">Besar Pinjaman × 1%</td>
                                    <td class="px-4 py-3">Biaya administrasi provisi bank standar 1%.</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">Bunga Pinjaman (Bulanan)</td>
                                    <td class="px-4 py-3 font-mono bg-slate-50 text-slate-800">(Suku Bunga / 12) × 1 × Besar Pinjaman</td>
                                    <td class="px-4 py-3">Perhitungan bunga flat bulanan.</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">Deviasi Kurva S</td>
                                    <td class="px-4 py-3 font-mono bg-slate-50 text-slate-800">Realisasi Kumulatif (%) - Rencana Kumulatif (%)</td>
                                    <td class="px-4 py-3">Positif (+) = lebih cepat; Negatif (-) = terlambat.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="space-y-3 pt-2">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1">
                            <strong class="text-slate-900 block text-sm">Bagaimana jika durasi proyek berubah?</strong>
                            <p class="text-slate-600 leading-relaxed">
                                Masuk ke Edit Proyek &rarr; Tab Informasi & Asumsi &rarr; Ubah tanggal mulai atau selesai, lalu simpan. Sistem akan otomatis menyesuaikan jumlah kolom bulan cashflow dan timeline minggu jadwal.
                            </p>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-1">
                            <strong class="text-slate-900 block text-sm">Apakah nilai otomatis bisa diubah manual?</strong>
                            <p class="text-slate-600 leading-relaxed">
                                Ya! Sistem memiliki fitur <em>manual override</em>. Jika Anda mengisi nominal langsung pada baris kalkulasi otomatis, sistem akan menyimpan nilai manual Anda tanpa menimpanya kembali.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- FOOTER --}}
        <div class="py-4 text-center text-xs text-slate-400">
            Telkom Property &copy; {{ date('Y') }} PT Graha Sarana Duta. All rights reserved.
        </div>

    </div>
</x-app-layout>
