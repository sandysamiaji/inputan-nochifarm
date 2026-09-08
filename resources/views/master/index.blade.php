@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Section (Sesuai Mockup Layar 1) -->
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-maroon-800 hover:border-maroon-300 flex items-center justify-center shadow-sm transition-all active:scale-95">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Master</h1>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Pengaturan data acuan & operasional ayam</p>
            </div>
        </div>

        <!-- Quick Info Badge -->
        <div class="hidden sm:flex items-center gap-2 bg-white px-3.5 py-1.5 rounded-2xl border border-slate-200 shadow-sm text-xs">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="font-bold text-slate-700">{{ $totalChickens }} Ekor</span>
            <span class="text-slate-400">•</span>
            <span class="text-maroon-800 font-bold">Umur {{ $avgAgeWeeks }} Minggu</span>
        </div>
    </div>

    <!-- Banner Info Singkat -->
    <div class="farm-card p-4 sm:p-5 bg-gradient-to-r from-maroon-800 to-rose-900 text-white rounded-3xl shadow-md">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/15 text-rose-100 text-[11px] font-bold">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-300"></i>
                    Pusat Konfigurasi Farm
                </div>
                <h2 class="text-base sm:text-lg font-black tracking-tight">{{ $farmName }}</h2>
                <p class="text-xs text-rose-100 max-w-xl font-medium">
                    Atur pesan penyemangat dashboard, fase umur ayam, standar produksi harian, pakan, hingga profil kandang.
                </p>
            </div>
            <a href="{{ route('master.info-farm') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-maroon-900 hover:bg-rose-50 text-xs font-bold shadow-sm transition-all active:scale-95 shrink-0 self-start sm:self-center">
                <i data-lucide="edit-3" class="w-3.5 h-3.5 text-maroon-800"></i>
                <span>Edit Info Farm</span>
            </a>
        </div>
    </div>

    <!-- 6 KARTU MASTER DATA (Sesuai Mockup Gambar Layar 1) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">

        <!-- 1. Info Farm (Pengaturan Informasi Umum Peternakan & Dashboard) -->
        <a href="{{ route('master.info-farm') }}" class="farm-card farm-card-interactive p-4 sm:p-5 flex items-center justify-between gap-4 group">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 border border-blue-200/80 flex items-center justify-center shrink-0 text-blue-600 shadow-inner group-hover:scale-110 transition-transform">
                    <i data-lucide="info" class="w-6 h-6 stroke-[2.2]"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 group-hover:text-maroon-800 transition-colors">Info Farm</h3>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">Informasi umum peternakan & penyemangat dashboard</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-slate-50 group-hover:bg-maroon-50 text-slate-400 group-hover:text-maroon-700 flex items-center justify-center transition-colors shrink-0">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </div>
        </a>

        <!-- 2. Blok & Klotter (Flock) (Data Blok Kandang & Klotter) -->
        <a href="{{ route('master.flocks') }}" class="farm-card farm-card-interactive p-4 sm:p-5 flex items-center justify-between gap-4 group">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 border border-purple-200/80 flex items-center justify-center shrink-0 text-purple-600 shadow-inner group-hover:scale-110 transition-transform">
                    <i data-lucide="layers" class="w-6 h-6 stroke-[2.2]"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 group-hover:text-maroon-800 transition-colors">Blok & Klotter (Flock)</h3>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">Data blok kandang & populasi klotter ayam</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-slate-50 group-hover:bg-maroon-50 text-slate-400 group-hover:text-maroon-700 flex items-center justify-center transition-colors shrink-0">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </div>
        </a>

        <!-- 3. Standar Produksi & Pakan -->
        <a href="{{ route('master.standards') }}" class="farm-card farm-card-interactive p-4 sm:p-5 flex items-center justify-between gap-4 group">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-center shrink-0 text-emerald-600 shadow-inner group-hover:scale-110 transition-transform">
                    <i data-lucide="calendar-check" class="w-6 h-6 stroke-[2.2]"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 group-hover:text-maroon-800 transition-colors">Standar Produksi & Pakan</h3>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">Standar target produksi telur & gram pakan per ekor</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-slate-50 group-hover:bg-maroon-50 text-slate-400 group-hover:text-maroon-700 flex items-center justify-center transition-colors shrink-0">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </div>
        </a>

        <!-- 4. Standar BB (Standar Berat Badan Ayam) -->
        <a href="{{ route('master.standards') }}" class="farm-card farm-card-interactive p-4 sm:p-5 flex items-center justify-between gap-4 group">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-center shrink-0 text-amber-600 shadow-inner group-hover:scale-110 transition-transform">
                    <i data-lucide="scale" class="w-6 h-6 stroke-[2.2]"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 group-hover:text-maroon-800 transition-colors">Standar BB</h3>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">Standar target berat badan ayam layer per minggu</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-slate-50 group-hover:bg-maroon-50 text-slate-400 group-hover:text-maroon-700 flex items-center justify-center transition-colors shrink-0">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </div>
        </a>

        <!-- 5. Vaksin & Obat -->
        <a href="{{ route('master.medicines') }}" class="farm-card farm-card-interactive p-4 sm:p-5 flex items-center justify-between gap-4 group">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-center justify-center shrink-0 text-rose-600 shadow-inner group-hover:scale-110 transition-transform">
                    <i data-lucide="syringe" class="w-6 h-6 stroke-[2.2]"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 group-hover:text-maroon-800 transition-colors">Vaksin & Obat</h3>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">Jadwal vaksinasi, data obat & panduan dosis</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-slate-50 group-hover:bg-maroon-50 text-slate-400 group-hover:text-maroon-700 flex items-center justify-center transition-colors shrink-0">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </div>
        </a>

        <!-- 6. Pengaturan (Pengaturan Aplikasi) -->
        <a href="{{ route('master.settings') }}" class="farm-card farm-card-interactive p-4 sm:p-5 flex items-center justify-between gap-4 group">
            <div class="flex items-center gap-3.5 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0 text-slate-600 shadow-inner group-hover:scale-110 transition-transform">
                    <i data-lucide="settings" class="w-6 h-6 stroke-[2.2]"></i>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 group-hover:text-maroon-800 transition-colors">Pengaturan</h3>
                    <p class="text-xs text-slate-400 mt-0.5 truncate">Pengaturan sistem & preferensi aplikasi</p>
                </div>
            </div>
            <div class="w-8 h-8 rounded-full bg-slate-50 group-hover:bg-maroon-50 text-slate-400 group-hover:text-maroon-700 flex items-center justify-center transition-colors shrink-0">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </div>
        </a>

    </div>

</div>
@endsection
