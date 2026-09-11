@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- 1. KARTU SAMBUTAN & STATUS KANDANG (RESPONSIVE BANNER) -->
    <div class="farm-card p-4 sm:p-6 bg-gradient-to-r from-white via-white to-rose-50/60 border border-rose-100/70 shadow-sm relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            
            <!-- Ucapan & Status Ringkas -->
            <div class="space-y-1.5">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-maroon-50 text-maroon-800 text-xs font-bold border border-maroon-100 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Kandang Aktif
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                        <i data-lucide="layers" class="w-3.5 h-3.5 text-slate-400"></i>
                        Klotter 1 (3 Blok)
                    </span>
                    <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                        <i data-lucide="users" class="w-3.5 h-3.5 text-slate-400"></i>
                        {{ number_format($totalActiveChickens, 0, ',', '.') }} Ekor Ayam
                    </span>
                </div>

                <h2 class="text-lg sm:text-xl md:text-2xl font-black text-slate-900 flex items-center gap-2">
                    {{ $greeting }}, {{ $user ? $user->name : 'Petugas' }} 👋
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 font-medium">
                    {{ $motivationMsg }}
                </p>
                @if($isInfoActive && !empty($chickenStatusMsg))
                    <div class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs font-semibold shadow-xs">
                        <span class="flex h-2 w-2 relative shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                        <span>{{ $chickenStatusMsg }}</span>
                    </div>
                @endif
            </div>

            <!-- Kartu Penanggalan Kalender & Filter Cepat -->
            <div class="flex items-center gap-3 self-start md:self-center">
                <div class="text-center bg-white border border-slate-200 rounded-2xl px-4 py-2.5 shadow-sm min-w-[85px]">
                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-tight">{{ $namaHari }}</span>
                    <span class="block text-2xl sm:text-3xl font-black text-maroon-800 leading-none my-0.5">{{ $carbonDate->day }}</span>
                    <span class="block text-[11px] font-semibold text-slate-400">{{ $namaBulan }} {{ $carbonDate->year }}</span>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[10px] font-bold uppercase text-slate-400 tracking-wider">Pilih Tanggal</label>
                    <input type="date" value="{{ $selectedDate }}" onchange="window.location.href='?date=' + this.value" 
                           class="text-xs sm:text-sm text-maroon-800 font-bold bg-white border border-rose-200 rounded-xl px-3 py-2 outline-none cursor-pointer hover:border-maroon-700 shadow-sm transition-all">
                </div>
            </div>

        </div>
    </div>

    <!-- 2. RINGKASAN HARI INI (RESPONSIVE GRID: 2 COLS DI HP, 5 COLS DI LAPTOP/DESKTOP) -->
    <div>
        <div class="flex items-center justify-between mb-3 px-1">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-maroon-800"></div>
                <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-800">RINGKASAN HARI INI</h3>
                <span class="text-xs text-slate-400 font-medium hidden sm:inline">(Data per {{ $carbonDate->day }} {{ $namaBulan }} {{ $carbonDate->year }})</span>
            </div>
            <span class="text-xs text-maroon-800 font-semibold flex items-center gap-1">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Real-time DB
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">

            <!-- Card 1: Produksi Telur (Amber) -->
            <div class="farm-card p-3.5 sm:p-4 border-l-4 border-l-amber-500 bg-white flex flex-col justify-between">
                <div class="flex items-start justify-between gap-2">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100 shadow-xs">
                        <svg class="w-6 h-6 fill-amber-500 text-amber-500" viewBox="0 0 24 24">
                            <path d="M12 2C7.5 2 4 7.5 4 13.5C4 18.2 7.6 22 12 22C16.4 22 20 18.2 20 13.5C20 7.5 16.5 2 12 2Z" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="1.8"/>
                            <circle cx="12" cy="14" r="4" fill="currentColor" fill-opacity="0.8"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200">Masuk</span>
                </div>
                <div class="mt-3">
                    <p class="text-xs font-semibold text-slate-500">Produksi Telur</p>
                    <p class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-tight mt-0.5">
                        {{ number_format($totalEggCrates, 0, ',', '.') }} <span class="text-xs font-bold text-slate-500">Peti</span>
                    </p>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                        ({{ number_format($totalEggCount, 0, ',', '.') }} Butir)
                    </p>
                </div>
            </div>

            <!-- Card 2: Pemakaian Pakan (Emerald) -->
            <div class="farm-card p-3.5 sm:p-4 border-l-4 border-l-emerald-600 bg-white flex flex-col justify-between">
                <div class="flex items-start justify-between gap-2">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 shadow-xs">
                        <i data-lucide="package" class="w-5 h-5 stroke-[2.2]"></i>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">Kandang</span>
                </div>
                <div class="mt-3">
                    <p class="text-xs font-semibold text-slate-500">Pemakaian Pakan</p>
                    <p class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-tight mt-0.5">
                        {{ number_format($totalFeedKg, 0, ',', '.') }} <span class="text-xs font-bold text-slate-500">Kg</span>
                    </p>
                    <p class="text-[11px] text-emerald-700 font-semibold mt-0.5 flex items-center gap-1 truncate">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Pakan Layer
                    </p>
                </div>
            </div>

            <!-- Card 3: Mortalitas (Rose/Red) -->
            <div class="farm-card p-3.5 sm:p-4 border-l-4 border-l-rose-600 bg-white flex flex-col justify-between">
                <div class="flex items-start justify-between gap-2">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100 shadow-xs">
                        <i data-lucide="skull" class="w-5 h-5 stroke-[2.2]"></i>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200">Afkir/Mati</span>
                </div>
                <div class="mt-3">
                    <p class="text-xs font-semibold text-slate-500">Mortalitas</p>
                    <p class="text-lg sm:text-xl font-black text-rose-700 tracking-tight leading-tight mt-0.5">
                        {{ $totalMortalityCount }} <span class="text-xs font-bold text-slate-500">Ekor</span>
                    </p>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                        Kematian Harian
                    </p>
                </div>
            </div>

            <!-- Card 4: Berat Badan (Sky Blue) -->
            <div class="farm-card p-3.5 sm:p-4 border-l-4 border-l-sky-600 bg-white flex flex-col justify-between">
                <div class="flex items-start justify-between gap-2">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-100 shadow-xs">
                        <i data-lucide="scale" class="w-5 h-5 stroke-[2.2]"></i>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-sky-50 text-sky-700 border border-sky-200">Bobot</span>
                </div>
                <div class="mt-3">
                    <p class="text-xs font-semibold text-slate-500">Berat Badan</p>
                    <p class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-tight mt-0.5">
                        {{ number_format($averageWeightKg, 2, ',', '.') }} <span class="text-xs font-bold text-slate-500">Kg</span>
                    </p>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">
                        (Rata-rata Sampel)
                    </p>
                </div>
            </div>

            <!-- Card 5: Vaksin / Obat (Maroon) -->
            <div class="col-span-2 md:col-span-1 farm-card p-3.5 sm:p-4 border-l-4 border-l-maroon-800 bg-white flex flex-col justify-between">
                <div class="flex items-start justify-between gap-2">
                    <div class="w-10 h-10 rounded-xl bg-maroon-50 text-maroon-800 flex items-center justify-center shrink-0 border border-maroon-100 shadow-xs">
                        <i data-lucide="syringe" class="w-5 h-5 stroke-[2.2]"></i>
                    </div>
                    <button onclick="openModal('modalVaksin')" class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-maroon-50 text-maroon-800 hover:bg-maroon-100 border border-maroon-200 transition-colors">
                        + Catat
                    </button>
                </div>
                <div class="mt-3">
                    <p class="text-xs font-semibold text-slate-500">Vaksin / Obat</p>
                    <p class="text-lg sm:text-xl font-black text-slate-900 tracking-tight leading-tight mt-0.5">
                        {{ $totalHealthActivities }} <span class="text-xs font-bold text-slate-500">Kegiatan</span>
                    </p>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                        Perlakuan Medis
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- 3. MAIN SECTION: LAYOUT 2 KOLOM DI LAPTOP / DESKTOP -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- KOLOM KIRI (7 Kolom di Desktop): AKSI CEPAT & STATUS BLOK -->
        <div class="lg:col-span-7 xl:col-span-8 space-y-6">

            <!-- Aksi Cepat Grid -->
            <div>
                <div class="flex items-center justify-between mb-3 px-1">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                        <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-800">AKSI CEPAT PENGINPUTAN</h3>
                    </div>
                    <span class="text-xs text-maroon-800 font-semibold flex items-center gap-1">
                        <i data-lucide="zap" class="w-3.5 h-3.5 fill-maroon-800"></i> Langsung Input
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">

                    <!-- 1. Input Produksi Telur -->
                    <div onclick="openModal('modalProduksi')" class="farm-card farm-card-interactive p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-200 shadow-xs">
                                <svg class="w-6 h-6 fill-amber-500 text-amber-500" viewBox="0 0 24 24">
                                    <path d="M12 2C7.5 2 4 7.5 4 13.5C4 18.2 7.6 22 12 22C16.4 22 20 18.2 20 13.5C20 7.5 16.5 2 12 2Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Input Produksi</h4>
                                <p class="text-xs text-slate-500">Catat pemasukan butir telur</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
                    </div>

                    <!-- 2. Input Pakan -->
                    <div onclick="openModal('modalPakan')" class="farm-card farm-card-interactive p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-200 shadow-xs">
                                <i data-lucide="wheat" class="w-6 h-6 stroke-[2.2]"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Input Pakan</h4>
                                <p class="text-xs text-slate-500">Catat pemakaian pakan (Kg)</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
                    </div>

                    <!-- 3. Input Mortalitas -->
                    <div onclick="openModal('modalMortalitas')" class="farm-card farm-card-interactive p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200 shadow-xs">
                                <i data-lucide="skull" class="w-6 h-6 stroke-[2.2]"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Input Mortalitas</h4>
                                <p class="text-xs text-slate-500">Catat kematian ayam (Ekor)</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
                    </div>

                    <!-- 4. Input Berat Badan -->
                    <div onclick="openModal('modalBobot')" class="farm-card farm-card-interactive p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0 border border-sky-200 shadow-xs">
                                <i data-lucide="scale" class="w-6 h-6 stroke-[2.2]"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Input Berat Badan</h4>
                                <p class="text-xs text-slate-500">Catat sampel bobot ayam</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
                    </div>

                    <!-- 5. Input Vaksin & Obat -->
                    <div onclick="openModal('modalVaksin')" class="farm-card farm-card-interactive p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-maroon-50 text-maroon-800 flex items-center justify-center shrink-0 border border-maroon-200 shadow-xs">
                                <i data-lucide="syringe" class="w-6 h-6 stroke-[2.2]"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Input Vaksin & Obat</h4>
                                <p class="text-xs text-slate-500">Catat vaksinasi & vitamin</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400"></i>
                    </div>

                    <!-- 6. Input / Kelola Blok & Klotter -->
                    <a href="{{ route('master.flocks') }}" class="farm-card farm-card-interactive p-4 flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 border border-purple-200 shadow-xs group-hover:scale-105 transition-transform">
                                <i data-lucide="layers" class="w-6 h-6 stroke-[2.2]"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 group-hover:text-purple-700 transition-colors">Blok & Klotter</h4>
                                <p class="text-xs text-slate-500">Kelola kandang, populasi & umur</p>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-5 h-5 text-slate-400 group-hover:text-purple-700 transition-colors"></i>
                    </a>

                </div>
            </div>

            <!-- STATUS BLOK KANDANG (Info Rinci Per Blok Sesuai Mockup) -->
            <div>
                <div class="flex items-center justify-between mb-3 px-1">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-600"></div>
                        <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-800">STATUS BLOK KANDANG AKTIF</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 font-semibold hidden sm:inline">{{ $coops->count() }} Blok Terdaftar</span>
                        <a href="{{ route('master.flocks') }}" class="text-xs text-maroon-800 hover:text-maroon-900 font-bold flex items-center gap-1 bg-maroon-50 hover:bg-maroon-100 px-2.5 py-1 rounded-lg border border-maroon-200 transition-colors">
                            <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                            <span>Kelola Blok & Klotter</span>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach($coops as $coop)
                        @php
                            $capacityPercent = $coop->capacity > 0 ? min(100, round(($coop->active_chickens / $coop->capacity) * 100)) : 0;
                        @endphp
                        <div class="farm-card p-4 bg-white border border-slate-200 hover:border-maroon-200 transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-black text-slate-900 text-sm sm:text-base flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-maroon-800"></span>
                                    {{ $coop->name }}
                                </h4>
                                <span class="text-[11px] font-bold px-2 py-0.5 rounded-md bg-rose-50 text-maroon-800 border border-rose-100">
                                    {{ $coop->chicken_age_weeks }} Minggu
                                </span>
                            </div>

                            <div class="space-y-2 mt-3">
                                <div class="flex justify-between text-xs text-slate-500">
                                    <span>Kapasitas Aktif:</span>
                                    <b class="text-slate-900 font-bold">{{ number_format($coop->active_chickens, 0, ',', '.') }} / {{ number_format($coop->capacity, 0, ',', '.') }}</b>
                                </div>
                                <!-- Progress Bar -->
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="bg-maroon-700 h-full rounded-full transition-all" style="width: {{ $capacityPercent }}%"></div>
                                </div>
                            </div>

                            <button onclick="openModalForCoop('modalProduksi', {{ $coop->id }})" 
                                    class="w-full mt-4 py-2 rounded-xl bg-slate-50 hover:bg-maroon-50 text-slate-700 hover:text-maroon-800 font-bold text-xs border border-slate-200 hover:border-maroon-200 transition-all flex items-center justify-center gap-1.5">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                <span>Input Telur Blok Ini</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN (5 Kolom di Desktop): GUDANG INTEGRASI & AKTIVITAS TERAKHIR -->
        <div class="lg:col-span-5 xl:col-span-4 space-y-6">

            <!-- KARTU INTEGRASI GUDANG NOCHIFRAM (Stok Masuk Kandang vs Keluar Penjualan) -->
            <div class="farm-card p-4 sm:p-5 bg-gradient-to-br from-white to-slate-50 border border-slate-200">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-maroon-100 text-maroon-800 flex items-center justify-center">
                            <i data-lucide="warehouse" class="w-4 h-4"></i>
                        </div>
                        <h4 class="font-bold text-slate-800 text-xs sm:text-sm">Gudang & Integrasi Penjualan</h4>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">1 DB Terhubung</span>
                </div>

                <div class="space-y-3">
                    <!-- Gudang Telur -->
                    <div class="p-3 rounded-xl bg-amber-50/50 border border-amber-100">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Stok Telur Saat Ini
                            </span>
                            <span class="text-maroon-800 font-black text-sm">{{ number_format($currentEggStockCrates, 0, ',', '.') }} Peti</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between text-[11px] text-slate-500 pt-1.5 border-t border-amber-100/60 gap-1">
                            <span>Masuk: <b class="text-slate-700">{{ number_format($totalEggProducedAllTime, 0, ',', '.') }} Peti</b></span>
                            <span>Keluar: <b class="text-maroon-800 font-bold">{{ number_format($totalEggSoldAllTime, 0, ',', '.') }} Peti & {{ number_format($eggKgSold, 0, ',', '.') }} Kg Terjual</b></span>
                        </div>
                    </div>

                    <!-- Gudang Pakan -->
                    <div class="p-3 rounded-xl bg-emerald-50/50 border border-emerald-100">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Stok Pakan Saat Ini
                            </span>
                            <span class="text-emerald-700 font-black text-sm">{{ number_format($currentFeedStockKg, 0, ',', '.') }} Kg</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between text-[11px] text-slate-500 pt-1.5 border-t border-emerald-100/60 gap-1">
                            <span>Kandang: <b class="text-slate-700">{{ number_format($totalFeedUsedAllTime, 0, ',', '.') }} Kg</b></span>
                            <span>Terjual: <b class="text-emerald-800 font-bold">{{ number_format($feedKarungSold, 0, ',', '.') }} Karung ({{ number_format($feedKgSoldTotal, 0, ',', '.') }} Kg)</b></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AKTIVITAS TERAKHIR TIMELINE -->
            <div>
                <div class="flex items-center justify-between mb-3 px-1">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-maroon-800"></div>
                        <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-800">AKTIVITAS TERAKHIR</h3>
                    </div>
                    <a href="#aktivitas" onclick="showInfoToast('Daftar riwayat lengkap seluruh aktivitas hari ini')" class="text-xs text-maroon-800 font-bold hover:underline">
                        Lihat semua
                    </a>
                </div>

                <div class="farm-card divide-y divide-slate-100 overflow-hidden shadow-xs">
                    @forelse($activities as $act)
                        <div class="p-3.5 sm:p-4 flex items-center justify-between hover:bg-slate-50/80 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                @if($act['category'] === 'egg')
                                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100 shadow-xs">
                                        <svg class="w-4 h-4 fill-amber-500 text-amber-500" viewBox="0 0 24 24">
                                            <path d="M12 2C7.5 2 4 7.5 4 13.5C4 18.2 7.6 22 12 22C16.4 22 20 18.2 20 13.5C20 7.5 16.5 2 12 2Z" fill="currentColor"/>
                                        </svg>
                                    </div>
                                @elseif($act['category'] === 'feed')
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100 shadow-xs">
                                        <i data-lucide="package" class="w-4 h-4"></i>
                                    </div>
                                @elseif($act['category'] === 'mortality')
                                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-100 shadow-xs">
                                        <i data-lucide="skull" class="w-4 h-4"></i>
                                    </div>
                                @else
                                    <div class="w-9 h-9 rounded-xl bg-maroon-50 text-maroon-800 flex items-center justify-center shrink-0 border border-maroon-100 shadow-xs">
                                        <i data-lucide="syringe" class="w-4 h-4"></i>
                                    </div>
                                @endif

                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-bold text-slate-900 truncate">{{ $act['title'] }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $act['datetime'] }} • {{ $act['subtitle'] }}</p>
                                </div>
                            </div>

                            <div class="text-right shrink-0 ml-3">
                                <span class="text-xs sm:text-sm font-black {{ $act['category'] === 'egg' ? 'text-amber-600' : ($act['category'] === 'feed' ? 'text-emerald-600' : ($act['category'] === 'mortality' ? 'text-rose-600' : 'text-maroon-800')) }}">
                                    {{ $act['value'] }}
                                </span>
                                @if(!empty($act['subvalue']))
                                    <p class="text-[10.5px] text-slate-400 font-medium">{{ $act['subvalue'] }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400">
                            <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-1 text-slate-300"></i>
                            <p class="text-xs font-medium">Belum ada aktivitas tercatat pada tanggal ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

<!-- ========================================================================= -->
<!-- MODAL 1: INPUT PRODUKSI TELUR (RESPONSIVE DI HP & LAPTOP)                 -->
<!-- ========================================================================= -->
<div id="modalProduksi" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm opacity-0 invisible pointer-events-none transition-all duration-300 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl transform translate-y-full sm:translate-y-0 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <i data-lucide="egg" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-900 text-base">Input Produksi Telur</h3>
                    <p class="text-[11px] text-slate-400">Catat jumlah panen telur masuk dari kandang</p>
                </div>
            </div>
            <button onclick="closeModal('modalProduksi')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('production.store') }}" method="POST" class="py-4 space-y-4">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}">

            <!-- 1. Pilih Klotter & Blok -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Klotter & Blok (Wajib)</label>
                <select name="coop_id" id="prodCoopSelect" required onchange="updateCoopInfo(this)"
                        class="w-full text-xs sm:text-sm font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 focus:ring-1 focus:ring-maroon-800 outline-none bg-slate-50">
                    <option value="">-- Pilih Blok Kandang --</option>
                    @foreach($coops as $coop)
                        <option value="{{ $coop->id }}" data-capacity="{{ $coop->capacity }}" data-active="{{ $coop->active_chickens }}" data-age="{{ $coop->chicken_age_weeks }}">
                            {{ $coop->flock ? $coop->flock->name . ' - ' : '' }}{{ $coop->name }} ({{ number_format($coop->active_chickens, 0, ',', '.') }} Ekor)
                        </option>
                    @endforeach
                </select>

                <!-- Info Blok Badge -->
                <div id="coopInfoBox" class="mt-2.5 p-3 bg-rose-50/70 border border-rose-100 rounded-xl flex items-center justify-between text-xs text-slate-600 hidden">
                    <div>
                        <span class="font-bold text-maroon-900 block" id="coopActiveText">Kapasitas Aktif: -</span>
                        <span class="text-slate-500 text-[11px]">Umur Ayam: <b id="coopAgeText" class="text-slate-800">-</b></span>
                    </div>
                    <span class="px-2.5 py-1 bg-white text-maroon-800 font-bold rounded-lg border border-rose-200 text-xs shadow-xs">Blok Terpilih</span>
                </div>
            </div>

            <!-- 2. Grid Telur Masuk & Retak -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Telur Masuk (Butir)</label>
                    <div class="relative">
                        <input type="number" name="total_eggs" id="prodTotalEggs" required placeholder="Contoh: 2150"
                               oninput="calculateEggEstimates()"
                               class="w-full text-sm font-bold text-slate-900 px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 focus:ring-1 focus:ring-maroon-800 outline-none bg-slate-50">
                        <span class="absolute right-3.5 top-2.5 text-xs font-semibold text-slate-400">Butir</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Telur Retak / Pecah (Butir)</label>
                    <div class="relative">
                        <input type="number" name="broken_eggs" id="prodBrokenEggs" value="0" placeholder="0"
                               oninput="calculateEggEstimates()"
                               class="w-full text-sm font-bold text-rose-700 px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 focus:ring-1 focus:ring-maroon-800 outline-none bg-slate-50">
                        <span class="absolute right-3.5 top-2.5 text-xs font-semibold text-slate-400">Butir</span>
                    </div>
                </div>
            </div>

            <!-- 3. Estimasi Hasil Otomatis -->
            <div class="p-3.5 bg-amber-50/80 border border-amber-200 rounded-xl space-y-2 text-xs sm:text-sm">
                <div class="flex justify-between items-center text-slate-700 font-medium">
                    <span>Produktivitas Hen-Day (HD):</span>
                    <span id="dashCalcHD" class="px-2 py-0.5 rounded-md bg-amber-100 text-amber-900 font-black text-xs sm:text-sm">0%</span>
                </div>
                <div class="flex justify-between text-slate-600 font-medium">
                    <span>Telur Baik (Estimasi):</span>
                    <b id="calcGoodEggs" class="text-slate-900 font-bold">0 Butir</b>
                </div>
                <div class="flex justify-between text-slate-600 font-medium">
                    <span>Estimasi Peti:</span>
                    <b id="calcCrates" class="text-maroon-800 font-black text-sm sm:text-base">0 Peti</b>
                </div>
            </div>
            <input type="hidden" name="crates_count" id="prodCratesCount" value="0">

            <!-- 4. Keterangan Opsional -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan (Opsional)</label>
                <input type="text" name="notes" placeholder="Contoh: Panen pagi kondisi bagus"
                       class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalProduksi')" class="flex-1 py-2.5 sm:py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition-colors">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 sm:py-3 rounded-xl bg-maroon-800 hover:bg-maroon-900 text-white font-bold text-xs sm:text-sm shadow-md shadow-maroon-900/20 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan Produksi</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 2: INPUT PEMAKAIAN PAKAN                                            -->
<!-- ========================================================================= -->
<div id="modalPakan" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm opacity-0 invisible pointer-events-none transition-all duration-300 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl transform translate-y-full sm:translate-y-0 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i data-lucide="wheat" class="w-5 h-5"></i>
                </div>
                <h3 class="font-black text-slate-900 text-base">Input Pemakaian Pakan</h3>
            </div>
            <button onclick="closeModal('modalPakan')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('feed.store') }}" method="POST" class="py-4 space-y-4">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Blok Kandang</label>
                <select name="coop_id" class="w-full text-xs sm:text-sm font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                    <option value="">Semua Blok (Global)</option>
                    @foreach($coops as $coop)
                        <option value="{{ $coop->id }}">{{ $coop->name }} ({{ $coop->active_chickens }} Ekor)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Waktu Pemberian</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold cursor-pointer hover:bg-rose-50/50 has-[:checked]:bg-maroon-50 has-[:checked]:border-maroon-800 has-[:checked]:text-maroon-800 transition-all">
                        <input type="radio" name="feeding_time" value="Pagi" checked class="accent-maroon-800">
                        <span>Pagi (07:00)</span>
                    </label>
                    <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold cursor-pointer hover:bg-rose-50/50 has-[:checked]:bg-maroon-50 has-[:checked]:border-maroon-800 has-[:checked]:text-maroon-800 transition-all">
                        <input type="radio" name="feeding_time" value="Sore" class="accent-maroon-800">
                        <span>Sore (15:30)</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Pakan</label>
                <input type="text" name="feed_name" value="Pakan Layer" required
                       class="w-full text-xs sm:text-sm font-bold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jumlah Pakan (Kg)</label>
                <div class="relative">
                    <input type="number" step="0.1" name="quantity_kg" required placeholder="Contoh: 80"
                           class="w-full text-sm font-bold text-slate-900 px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                    <span class="absolute right-3.5 top-2.5 text-xs font-semibold text-slate-400">Kg</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Catatan</label>
                <input type="text" name="notes" placeholder="Opsional"
                       class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
            </div>

            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalPakan')" class="flex-1 py-2.5 sm:py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 sm:py-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs sm:text-sm shadow-md">
                    Simpan Pakan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 3: INPUT MORTALITAS                                                 -->
<!-- ========================================================================= -->
<div id="modalMortalitas" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm opacity-0 invisible pointer-events-none transition-all duration-300 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl transform translate-y-full sm:translate-y-0 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center">
                    <i data-lucide="skull" class="w-5 h-5"></i>
                </div>
                <h3 class="font-black text-slate-900 text-base">Input Mortalitas Ayam</h3>
            </div>
            <button onclick="closeModal('modalMortalitas')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('mortality.store') }}" method="POST" class="py-4 space-y-4">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Blok (Wajib)</label>
                <select name="coop_id" required class="w-full text-xs sm:text-sm font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                    <option value="">-- Pilih Blok --</option>
                    @foreach($coops as $coop)
                        <option value="{{ $coop->id }}">{{ $coop->name }} (Sisa {{ $coop->active_chickens }} Ekor)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jumlah Ayam Mati / Afkir</label>
                <div class="relative">
                    <input type="number" name="count" required min="1" placeholder="Contoh: 2"
                           class="w-full text-sm font-bold text-rose-700 px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                    <span class="absolute right-3.5 top-2.5 text-xs font-semibold text-slate-400">Ekor</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Status / Kategori</label>
                <select name="type" class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                    <option value="mati">Kematian (Mati)</option>
                    <option value="afkir">Afkir (Dipisahkan)</option>
                    <option value="sakit">Karantina Sakit</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Penyebab / Indikasi</label>
                <input type="text" name="cause" placeholder="Contoh: Stres panas wajar, kanibalisme, dll"
                       class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
            </div>

            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalMortalitas')" class="flex-1 py-2.5 sm:py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 sm:py-3 rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-bold text-xs sm:text-sm shadow-md">
                    Simpan Mortalitas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 4: INPUT BERAT BADAN                                                -->
<!-- ========================================================================= -->
<div id="modalBobot" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm opacity-0 invisible pointer-events-none transition-all duration-300 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl transform translate-y-full sm:translate-y-0 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center">
                    <i data-lucide="scale" class="w-5 h-5"></i>
                </div>
                <h3 class="font-black text-slate-900 text-base">Input Bobot Ayam</h3>
            </div>
            <button onclick="closeModal('modalBobot')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('weight.store') }}" method="POST" class="py-4 space-y-4">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih Blok Kandang</label>
                <select name="coop_id" required class="w-full text-xs sm:text-sm font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                    <option value="">-- Pilih Blok --</option>
                    @foreach($coops as $coop)
                        <option value="{{ $coop->id }}">{{ $coop->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Rata-rata Berat Badan (Kg)</label>
                <div class="relative">
                    <input type="number" step="0.001" name="average_weight_kg" required placeholder="Contoh: 1.620"
                           class="w-full text-sm font-bold text-slate-900 px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                    <span class="absolute right-3.5 top-2.5 text-xs font-semibold text-slate-400">Kg</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jumlah Sampel Timbang</label>
                <div class="relative">
                    <input type="number" name="sample_count" value="50" placeholder="50"
                           class="w-full text-xs sm:text-sm font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                    <span class="absolute right-3.5 top-2.5 text-xs font-semibold text-slate-400">Ekor</span>
                </div>
            </div>

            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalBobot')" class="flex-1 py-2.5 sm:py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 sm:py-3 rounded-xl bg-sky-700 hover:bg-sky-800 text-white font-bold text-xs sm:text-sm shadow-md">
                    Simpan Bobot
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL 5: INPUT VAKSIN & OBAT                                              -->
<!-- ========================================================================= -->
<div id="modalVaksin" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm opacity-0 invisible pointer-events-none transition-all duration-300 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl transform translate-y-full sm:translate-y-0 transition-transform duration-300 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-maroon-100 text-maroon-800 flex items-center justify-center">
                    <i data-lucide="syringe" class="w-5 h-5"></i>
                </div>
                <h3 class="font-black text-slate-900 text-base">Input Vaksin / Obat</h3>
            </div>
            <button onclick="closeModal('modalVaksin')" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="{{ route('health.store') }}" method="POST" class="py-4 space-y-4">
            @csrf
            <input type="hidden" name="date" value="{{ $selectedDate }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Blok Sasaran</label>
                <select name="coop_id" class="w-full text-xs sm:text-sm font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                    <option value="">Semua Blok (Kandang Keseluruhan)</option>
                    @foreach($coops as $coop)
                        <option value="{{ $coop->id }}">{{ $coop->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori</label>
                <select name="type" class="w-full text-xs sm:text-sm font-semibold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                    <option value="vitamin">Vitamin</option>
                    <option value="vaksin">Vaksin</option>
                    <option value="obat">Obat / Antibiotik</option>
                    <option value="disinfektan">Disinfektan</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Produk / Vaksin</label>
                <input type="text" name="medicine_name" required placeholder="Contoh: Vitamin B Complex, ND IB Vaccine"
                       class="w-full text-xs sm:text-sm font-bold px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Dosis / Jumlah</label>
                    <input type="text" name="dosage" placeholder="Contoh: 10 Botol"
                           class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Cara Aplikasi</label>
                    <select name="application_method" class="w-full text-xs sm:text-sm px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-maroon-800 outline-none bg-slate-50">
                        <option value="Air Minum">Air Minum</option>
                        <option value="Suntik">Suntik</option>
                        <option value="Tetes Mata">Tetes Mata</option>
                        <option value="Semprot (Spray)">Semprot (Spray)</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalVaksin')" class="flex-1 py-2.5 sm:py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 sm:py-3 rounded-xl bg-maroon-800 hover:bg-maroon-900 text-white font-bold text-xs sm:text-sm shadow-md">
                    Simpan Vaksin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Modal Open & Close Helpers
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('modal-active');
            const content = modal.querySelector('div');
            if (content) {
                content.classList.add('modal-content-active');
            }
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('modal-active');
            const content = modal.querySelector('div');
            if (content) {
                content.classList.remove('modal-content-active');
            }
        }
    }

    // Buka modal produksi langsung memilih blok tertentu
    function openModalForCoop(modalId, coopId) {
        openModal(modalId);
        const coopSelect = document.getElementById('prodCoopSelect');
        if (coopSelect) {
            coopSelect.value = coopId;
            updateCoopInfo(coopSelect);
        }
    }

    let dashSelectedCoopActive = 0;

    // Update info blok saat memilih blok di modal produksi
    function updateCoopInfo(selectElem) {
        const selected = selectElem.options[selectElem.selectedIndex];
        const infoBox = document.getElementById('coopInfoBox');
        if (selected && selected.value) {
            dashSelectedCoopActive = parseInt(selected.getAttribute('data-active') || '0');
            const age = selected.getAttribute('data-age') || '0';
            document.getElementById('coopActiveText').textContent = 'Kapasitas Aktif: ' + Number(dashSelectedCoopActive).toLocaleString('id-ID') + ' Ekor';
            document.getElementById('coopAgeText').textContent = age + ' Minggu';
            infoBox.classList.remove('hidden');
        } else {
            dashSelectedCoopActive = 0;
            infoBox.classList.add('hidden');
        }
        calculateEggEstimates();
    }

    // Kalkulasi estimasi butir baik dan peti secara otomatis
    function calculateEggEstimates() {
        const total = parseInt(document.getElementById('prodTotalEggs').value) || 0;
        const broken = parseInt(document.getElementById('prodBrokenEggs').value) || 0;
        const good = Math.max(0, total - broken);

        const crates = Math.round((good / 25) * 100) / 100;

        document.getElementById('calcGoodEggs').textContent = good.toLocaleString('id-ID') + ' Butir';
        document.getElementById('calcCrates').textContent = crates.toLocaleString('id-ID') + ' Peti';
        document.getElementById('prodCratesCount').value = crates;

        // Hitung Hen-Day (HD %)
        const hdElem = document.getElementById('dashCalcHD');
        if (hdElem) {
            if (dashSelectedCoopActive > 0 && total > 0) {
                const hd = ((total / dashSelectedCoopActive) * 100).toFixed(1);
                hdElem.textContent = hd + '%';
                if (hd >= 85) {
                    hdElem.className = "px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-black text-xs sm:text-sm";
                } else if (hd >= 70) {
                    hdElem.className = "px-2 py-0.5 rounded-md bg-amber-100 text-amber-800 font-black text-xs sm:text-sm";
                } else {
                    hdElem.className = "px-2 py-0.5 rounded-md bg-rose-100 text-rose-800 font-black text-xs sm:text-sm";
                }
            } else {
                hdElem.textContent = '0%';
                hdElem.className = "px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 font-black text-xs sm:text-sm";
            }
        }
    }
</script>
@endpush
