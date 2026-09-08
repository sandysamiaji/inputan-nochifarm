@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Section (Sesuai Gambar Mockup 1 & 3) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-maroon-800 text-white flex items-center justify-center shadow-md">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">REKAP DATA</h1>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium">Laporan & ringkasan analitik kandang</p>
                </div>
            </div>
        </div>

        <!-- Periode Badge Dropdown Trigger (Sesuai Mockup Layar 3) -->
        <div class="flex items-center gap-2">
            <button onclick="openModalPeriodPicker()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl bg-white border border-slate-200 hover:border-maroon-300 text-xs sm:text-sm font-bold text-slate-700 hover:text-maroon-800 shadow-sm transition-all active:scale-95">
                <i data-lucide="calendar" class="w-4 h-4 text-maroon-800"></i>
                <span>{{ $formattedRange }}</span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-400"></i>
            </button>
            <a href="{{ route('rekap.detail', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-2xl bg-maroon-800 hover:bg-maroon-900 text-white text-xs font-bold shadow-md shadow-maroon-900/20 transition-all active:scale-95">
                <i data-lucide="table" class="w-4 h-4 text-rose-200"></i>
                <span>Tabel Detail & Ekspor</span>
            </a>
        </div>
    </div>

    <!-- 1. KOTAK PEMILIH PERIODE (Sesuai Mockup Layar 1) -->
    <div class="farm-card p-5 sm:p-6">
        <form method="GET" action="{{ route('rekap.index') }}" id="formPeriodeFilter" class="space-y-4">
            <input type="hidden" name="preset" id="inputPreset" value="{{ $preset }}">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- Input Rentang Tanggal -->
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Periode</label>
                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-2xl p-2.5 focus-within:ring-2 focus-within:ring-maroon-800/20 focus-within:border-maroon-800 transition-all">
                        <input 
                            type="date" 
                            name="start_date" 
                            id="startDateInput"
                            value="{{ $startDate }}" 
                            class="bg-transparent border-0 text-xs sm:text-sm font-bold text-slate-700 focus:outline-none w-36"
                        >
                        <i data-lucide="arrow-right" class="w-4 h-4 text-slate-400 shrink-0"></i>
                        <input 
                            type="date" 
                            name="end_date" 
                            id="endDateInput"
                            value="{{ $endDate }}" 
                            class="bg-transparent border-0 text-xs sm:text-sm font-bold text-slate-700 focus:outline-none w-36"
                        >
                        <button type="button" onclick="openModalPeriodPicker()" class="ml-auto text-slate-400 hover:text-maroon-800 p-1">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Submit Tampilkan Rekap -->
                <div class="sm:self-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-maroon-800 hover:bg-maroon-900 text-white text-xs sm:text-sm font-bold shadow-md shadow-maroon-900/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <span>Tampilkan Rekap</span>
                    </button>
                </div>
            </div>

            <!-- Tombol Preset Periode (Sesuai Mockup Layar 1) -->
            <div>
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Preset Periode</span>
                <div class="flex flex-wrap gap-2">
                    @php
                        $presets = [
                            'hari_ini' => 'Hari Ini',
                            'kemarin' => 'Kemarin',
                            '7_hari' => '7 Hari Terakhir',
                            '30_hari' => '30 Hari Terakhir',
                            'bulan_ini' => 'Bulan Ini',
                            'bulan_lalu' => 'Bulan Lalu',
                            'custom' => 'Custom'
                        ];
                    @endphp
                    @foreach($presets as $key => $label)
                        <button 
                            type="button" 
                            onclick="applyPreset('{{ $key }}')" 
                            class="px-3 py-1.5 rounded-xl text-xs font-semibold border transition-all active:scale-95 {{ $preset === $key ? 'bg-maroon-800 text-white border-maroon-800 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    <!-- 2. 5 KARTU RINGKASAN METRIK (Sesuai Mockup Layar 3: Telur, Pakan, Mortalitas, Bobot, Vaksin) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">

        <!-- 1. Produksi Telur -->
        <a href="{{ route('rekap.detail', ['tab' => 'produksi', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="farm-card farm-card-interactive p-4 sm:p-5 relative overflow-hidden group col-span-2 sm:col-span-1">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Produksi Telur</span>
                    <div class="text-lg sm:text-xl font-black text-slate-900 mt-1">
                        {{ number_format($totalTelurPeti, 0, ',', '.') }} <span class="text-xs font-bold text-slate-500">Peti</span>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-0.5">
                        ({{ number_format($totalTelurButir, 0, ',', '.') }} Butir)
                    </div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 fill-amber-500 drop-shadow-sm" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C8.13 2 5 6.48 5 12c0 4.42 3.13 8 7 8s7-3.58 7-8c0-5.52-3.13-10-7-10z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-[10px] text-amber-700 font-bold flex items-center gap-1">
                <span>Lihat rincian harian</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

        <!-- 2. Pemakaian Pakan -->
        <a href="{{ route('rekap.detail', ['tab' => 'pakan', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="farm-card farm-card-interactive p-4 sm:p-5 relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pemakaian Pakan</span>
                    <div class="text-lg sm:text-xl font-black text-slate-900 mt-1">
                        {{ number_format($totalPakanKg, 0, ',', '.') }} <span class="text-xs font-bold text-slate-500">Kg</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-200/80 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 fill-emerald-600 drop-shadow-sm" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 6h-2.28a4.99 4.99 0 0 0-9.44 0H5a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3zm-7-2c1.3 0 2.4.84 2.82 2h-5.64A3.003 3.003 0 0 1 12 4zm0 13a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-[10px] text-emerald-700 font-bold flex items-center gap-1">
                <span>Rincian konsumsi</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

        <!-- 3. Mortalitas -->
        <a href="{{ route('rekap.detail', ['tab' => 'mortalitas', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="farm-card farm-card-interactive p-4 sm:p-5 relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Mortalitas</span>
                    <div class="text-lg sm:text-xl font-black text-rose-700 mt-1">
                        {{ number_format($totalMortalitas, 0, ',', '.') }} <span class="text-xs font-bold text-slate-500">Ekor</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-center justify-center shrink-0 text-rose-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="alert-triangle" class="w-6 h-6 stroke-[2.2]"></i>
                </div>
            </div>
            <div class="mt-3 text-[10px] text-rose-700 font-bold flex items-center gap-1">
                <span>Catatan kematian</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

        <!-- 4. Berat Badan -->
        <a href="{{ route('rekap.detail', ['tab' => 'bobot', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="farm-card farm-card-interactive p-4 sm:p-5 relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Berat Badan</span>
                    <div class="text-lg sm:text-xl font-black text-slate-900 mt-1">
                        {{ number_format($avgBobot, 2, ',', '.') }} <span class="text-xs font-bold text-slate-500">Kg</span>
                    </div>
                    <div class="text-[10px] text-slate-400 mt-0.5">(Rata-rata)</div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-blue-50 border border-blue-200/80 flex items-center justify-center shrink-0 text-blue-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="scale" class="w-6 h-6 stroke-[2.2]"></i>
                </div>
            </div>
            <div class="mt-3 text-[10px] text-blue-700 font-bold flex items-center gap-1">
                <span>Sampling bobot</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

        <!-- 5. Vaksin / Obat -->
        <a href="{{ route('rekap.detail', ['tab' => 'vaksin', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="farm-card farm-card-interactive p-4 sm:p-5 relative overflow-hidden group col-span-2 sm:col-span-1">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Vaksin / Obat</span>
                    <div class="text-lg sm:text-xl font-black text-slate-900 mt-1">
                        {{ $totalVaksinKegiatan }} <span class="text-xs font-bold text-slate-500">Kegiatan</span>
                    </div>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-200/80 flex items-center justify-center shrink-0 text-purple-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="syringe" class="w-6 h-6 stroke-[2.2]"></i>
                </div>
            </div>
            <div class="mt-3 text-[10px] text-purple-700 font-bold flex items-center gap-1">
                <span>Jadwal & histori</span>
                <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </div>
        </a>

    </div>

    <!-- 3. GRAFIK TREN INTERAKTIF (Sesuai Mockup Layar 3: Grafik Produksi Telur) -->
    <div class="farm-card p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <h3 class="font-extrabold text-slate-800 text-sm sm:text-base tracking-tight" id="chartTitle">GRAFIK PRODUKSI TELUR</h3>
                <p class="text-[11px] text-slate-400">Tren harian performa kandang pada periode terpilih</p>
            </div>

            <!-- Filter Switcher Grafik -->
            <div class="flex items-center gap-2">
                <select id="selectChartMetric" onchange="updateChartMetric(this.value)" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 bg-white shadow-sm focus:ring-2 focus:ring-maroon-800/20 focus:border-maroon-800">
                    <option value="egg_peti" selected>Produksi Telur (Peti)</option>
                    <option value="egg_butir">Produksi Telur (Butir)</option>
                    <option value="feed">Pemakaian Pakan (Kg)</option>
                    <option value="mortality">Mortalitas (Ekor)</option>
                </select>
                <span id="chartUnitBadge" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">
                    Peti
                </span>
            </div>
        </div>

        <!-- Canvas Chart -->
        <div class="mt-5 relative w-full" style="height: 280px;">
            <canvas id="rekapTrendChart"></canvas>
        </div>
    </div>

    <!-- 4. BANNER AJAKAN KE REKAP DETAIL -->
    <div class="farm-card p-5 bg-gradient-to-r from-rose-50/60 via-white to-amber-50/40 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-maroon-800 text-white flex items-center justify-center shrink-0 shadow-md">
                <i data-lucide="table-properties" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="text-sm sm:text-base font-extrabold text-slate-800">Lihat Tabel Rekapitulasi Lengkap</h4>
                <p class="text-xs text-slate-500">Tersedia tabel produksi harian, konsumsi pakan, kematian, bobot, dan ekspor ke Excel/PDF.</p>
            </div>
        </div>
        <a href="{{ route('rekap.detail', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-maroon-800 hover:bg-maroon-900 text-white text-xs sm:text-sm font-bold shadow-md transition-all active:scale-95 text-center shrink-0">
            Buka Rekap Detail & Ekspor →
        </a>
    </div>

</div>

<!-- ========================================================================= -->
<!-- MODAL / DRAWER PILIH PERIODE & KALENDER (Sesuai Gambar Mockup 2 Layar 2) -->
<!-- ========================================================================= -->
<div id="modalPeriodPicker" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm opacity-0 invisible pointer-events-none transition-all duration-300 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl transform translate-y-full sm:translate-y-0 transition-transform duration-300 max-h-[92vh] overflow-y-auto">
        
        <!-- Header Modal -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-extrabold text-slate-800 text-sm sm:text-base">Pilih Periode</h3>
            <button onclick="closeModalPeriodPicker()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('rekap.index') }}" class="mt-4 space-y-4">
            <!-- Tabs Modal: Preset | Custom -->
            <div class="grid grid-cols-2 p-1 bg-slate-100 rounded-xl text-xs font-bold text-center">
                <button type="button" onclick="switchPeriodTab('preset')" id="btnTabPreset" class="py-1.5 rounded-lg bg-white text-maroon-800 shadow-sm transition-all">
                    Preset
                </button>
                <button type="button" onclick="switchPeriodTab('custom')" id="btnTabCustom" class="py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition-all">
                    Custom
                </button>
            </div>

            <!-- Tab Content: Preset Buttons -->
            <div id="contentPeriodPreset" class="space-y-2 py-1">
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="selectModalPreset('hari_ini')" class="p-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:border-maroon-800 hover:bg-rose-50/50 transition-all text-center">
                        Hari Ini
                    </button>
                    <button type="button" onclick="selectModalPreset('kemarin')" class="p-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:border-maroon-800 hover:bg-rose-50/50 transition-all text-center">
                        Kemarin
                    </button>
                    <button type="button" onclick="selectModalPreset('7_hari')" class="p-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:border-maroon-800 hover:bg-rose-50/50 transition-all text-center">
                        7 Hari Terakhir
                    </button>
                    <button type="button" onclick="selectModalPreset('30_hari')" class="p-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:border-maroon-800 hover:bg-rose-50/50 transition-all text-center">
                        30 Hari Terakhir
                    </button>
                    <button type="button" onclick="selectModalPreset('bulan_ini')" class="p-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:border-maroon-800 hover:bg-rose-50/50 transition-all text-center">
                        Bulan Ini
                    </button>
                    <button type="button" onclick="selectModalPreset('bulan_lalu')" class="p-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:border-maroon-800 hover:bg-rose-50/50 transition-all text-center">
                        Bulan Lalu
                    </button>
                </div>
            </div>

            <!-- Tab Content: Custom Range & Kalender (Sesuai Mockup Layar 2) -->
            <div id="contentPeriodCustom" class="hidden space-y-3 py-1">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Mulai</label>
                        <input type="date" name="start_date" id="modalStartDate" value="{{ $startDate }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Sampai</label>
                        <input type="date" name="end_date" id="modalEndDate" value="{{ $endDate }}" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-700">
                    </div>
                </div>

                <!-- Mini Kalender Interaktif (Agustus 2026 / Bulan Aktif) -->
                <div class="p-3 bg-slate-50 border border-slate-100 rounded-2xl">
                    <div class="flex items-center justify-between text-xs font-extrabold text-slate-700 mb-2">
                        <span>Agustus 2026</span>
                        <span class="text-[10px] text-maroon-800 font-semibold">Rentang Aktif</span>
                    </div>
                    <!-- Hari header -->
                    <div class="grid grid-cols-7 text-center text-[10px] font-bold text-slate-400 mb-1">
                        <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                    </div>
                    <!-- Grid tanggal -->
                    <div class="grid grid-cols-7 gap-1 text-center text-xs">
                        <span class="text-slate-300 py-1"></span><span class="text-slate-300 py-1"></span><span class="text-slate-300 py-1"></span><span class="text-slate-300 py-1"></span>
                        <span class="py-1">1</span><span class="py-1">2</span><span class="py-1">3</span>
                        <span class="py-1">4</span><span class="py-1">5</span><span class="py-1">6</span><span class="py-1">7</span><span class="py-1">8</span><span class="py-1">9</span>
                        <!-- 10 s/d 18 Terpilih (Maroon Highlight) -->
                        <span class="py-1 font-extrabold bg-maroon-800 text-white rounded-l-lg cursor-pointer">10</span>
                        <span class="py-1 font-bold bg-rose-100 text-maroon-900 cursor-pointer">11</span>
                        <span class="py-1 font-bold bg-rose-100 text-maroon-900 cursor-pointer">12</span>
                        <span class="py-1 font-bold bg-rose-100 text-maroon-900 cursor-pointer">13</span>
                        <span class="py-1 font-bold bg-rose-100 text-maroon-900 cursor-pointer">14</span>
                        <span class="py-1 font-bold bg-rose-100 text-maroon-900 cursor-pointer">15</span>
                        <span class="py-1 font-bold bg-rose-100 text-maroon-900 cursor-pointer">16</span>
                        <span class="py-1 font-bold bg-rose-100 text-maroon-900 cursor-pointer">17</span>
                        <span class="py-1 font-extrabold bg-maroon-800 text-white rounded-r-lg cursor-pointer">18</span>
                        <span class="py-1">19</span><span class="py-1">20</span><span class="py-1">21</span><span class="py-1">22</span><span class="py-1">23</span><span class="py-1">24</span>
                        <span class="py-1">25</span><span class="py-1">26</span><span class="py-1">27</span><span class="py-1">28</span><span class="py-1">29</span><span class="py-1">30</span><span class="py-1">31</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Terapkan Periode -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-2xl bg-maroon-800 hover:bg-maroon-900 text-white font-bold text-sm shadow-md transition-all active:scale-98">
                    Terapkan Periode
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script>
    // 1. DATA UNTUK GRAFIK
    const chartLabels = {!! json_encode($chartLabels) !!};
    const chartDataSets = {
        'egg_peti': {
            label: 'Produksi Telur (Peti)',
            unit: 'Peti',
            title: 'GRAFIK PRODUKSI TELUR',
            data: {!! json_encode($chartEggPeti) !!},
            borderColor: '#800020',
            backgroundColor: 'rgba(128, 0, 32, 0.08)',
        },
        'egg_butir': {
            label: 'Produksi Telur (Butir)',
            unit: 'Butir',
            title: 'GRAFIK BUTIR TELUR',
            data: {!! json_encode($chartEggButir) !!},
            borderColor: '#b8324b',
            backgroundColor: 'rgba(184, 50, 75, 0.08)',
        },
        'feed': {
            label: 'Pemakaian Pakan (Kg)',
            unit: 'Kg',
            title: 'GRAFIK PEMAKAIAN PAKAN',
            data: {!! json_encode($chartFeedKg) !!},
            borderColor: '#059669',
            backgroundColor: 'rgba(5, 150, 105, 0.08)',
        },
        'mortality': {
            label: 'Mortalitas Ayam (Ekor)',
            unit: 'Ekor',
            title: 'GRAFIK MORTALITAS AYAM',
            data: {!! json_encode($chartMortality) !!},
            borderColor: '#e11d48',
            backgroundColor: 'rgba(225, 29, 72, 0.08)',
        }
    };

    let currentChart = null;

    function renderTrendChart(metricKey) {
        const ctx = document.getElementById('rekapTrendChart').getContext('2d');
        const metric = chartDataSets[metricKey];

        if (currentChart) {
            currentChart.destroy();
        }

        currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: metric.label,
                    data: metric.data,
                    borderColor: metric.borderColor,
                    backgroundColor: metric.backgroundColor,
                    borderWidth: 2.8,
                    tension: 0.38, // Smooth curve matching mockup
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: metric.borderColor,
                    pointBorderWidth: 2.5,
                    pointRadius: 4.5,
                    pointHoverRadius: 6.5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { size: 12, family: 'Plus Jakarta Sans', weight: 'bold' },
                        bodyFont: { size: 12, family: 'Plus Jakarta Sans' },
                        padding: 10,
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString('id-ID') + ' ' + metric.unit;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11, family: 'Plus Jakarta Sans', weight: '600' },
                            color: '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: false,
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: { size: 10, family: 'Plus Jakarta Sans' },
                            color: '#94a3b8',
                            callback: function(val) {
                                return val.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    function updateChartMetric(val) {
        const metric = chartDataSets[val];
        document.getElementById('chartTitle').textContent = metric.title;
        document.getElementById('chartUnitBadge').textContent = metric.unit;
        renderTrendChart(val);
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderTrendChart('egg_peti');
    });

    // 2. PRESET HANDLER
    function applyPreset(presetKey) {
        document.getElementById('inputPreset').value = presetKey;
        document.getElementById('formPeriodeFilter').submit();
    }

    // 3. MODAL LOGIC
    function openModalPeriodPicker() {
        const modal = document.getElementById('modalPeriodPicker');
        const content = modal.querySelector('div');
        modal.classList.add('modal-active');
        content.classList.add('modal-content-active');
    }

    function closeModalPeriodPicker() {
        const modal = document.getElementById('modalPeriodPicker');
        const content = modal.querySelector('div');
        modal.classList.remove('modal-active');
        content.classList.remove('modal-content-active');
    }

    function switchPeriodTab(tab) {
        const btnPreset = document.getElementById('btnTabPreset');
        const btnCustom = document.getElementById('btnTabCustom');
        const contentPreset = document.getElementById('contentPeriodPreset');
        const contentCustom = document.getElementById('contentPeriodCustom');

        if (tab === 'preset') {
            btnPreset.className = 'py-1.5 rounded-lg bg-white text-maroon-800 shadow-sm transition-all';
            btnCustom.className = 'py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition-all';
            contentPreset.classList.remove('hidden');
            contentCustom.classList.add('hidden');
        } else {
            btnCustom.className = 'py-1.5 rounded-lg bg-white text-maroon-800 shadow-sm transition-all';
            btnPreset.className = 'py-1.5 rounded-lg text-slate-500 hover:text-slate-800 transition-all';
            contentCustom.classList.remove('hidden');
            contentPreset.classList.add('hidden');
        }
    }

    function selectModalPreset(key) {
        window.location.href = `{{ route('rekap.index') }}?preset=${key}`;
    }
</script>
@endpush
@endsection
