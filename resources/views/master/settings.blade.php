@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation Header -->
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('master.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-700 hover:text-maroon-800 hover:border-maroon-300 flex items-center justify-center shadow-sm transition-all active:scale-95">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight">Pengaturan Aplikasi</h1>
                <p class="text-xs text-slate-400">Preferensi tampilan sistem & informasi aplikasi</p>
            </div>
        </div>
    </div>

    <div class="farm-card p-5 sm:p-6 space-y-5">
        <div class="space-y-4">
            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Tema Warna Antarmuka</h3>
                    <p class="text-xs text-slate-400">Tema default yang diterapkan pada seluruh modul</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded-full bg-maroon-800 border-2 border-white shadow-xs"></span>
                    <span class="text-xs font-bold text-maroon-800">Merah Marun & Putih</span>
                </div>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Versi Aplikasi</h3>
                    <p class="text-xs text-slate-400">Nochi Farm Input System</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg text-xs font-extrabold bg-slate-100 text-slate-700">
                    v1.0.0 Stable
                </span>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Koneksi Database Bersama</h3>
                    <p class="text-xs text-slate-400">Terintegrasi dengan sistem penjualan & gudang Nochi Farm</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-emerald-700">Terhubung (MySQL nochifram)</span>
                </div>
            </div>
        </div>

        <div class="pt-2 flex justify-start">
            <a href="{{ route('master.info-farm') }}" class="px-5 py-2.5 rounded-xl bg-maroon-800 hover:bg-maroon-900 text-white text-xs font-bold shadow-md transition-all active:scale-95">
                Buka Pengaturan Info Farm & Tampilan
            </a>
        </div>
    </div>

</div>
@endsection
