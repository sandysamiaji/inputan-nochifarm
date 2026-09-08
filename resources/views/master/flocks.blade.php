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
                <h1 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight">Blok & Klotter (Flock)</h1>
                <p class="text-xs text-slate-400">Manajemen data kandang, populasi ayam & umur flock</p>
            </div>
        </div>
    </div>

    <!-- Info Klotter Card -->
    @foreach($flocks as $flock)
        <div class="farm-card p-5 sm:p-6 bg-gradient-to-r from-purple-50/50 via-white to-white border border-purple-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center font-black text-lg shadow-inner">
                        {{ $flock->code }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base sm:text-lg font-bold text-slate-900">{{ $flock->name }}</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">AKTIF</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Ras: <span class="font-bold text-slate-700">{{ $flock->breed ?? 'Lohmann Brown' }}</span> • Mulai: <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($flock->start_date)->translatedFormat('d M Y') }}</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-xs">
                    <div class="text-right">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Populasi Awal</span>
                        <span class="font-bold text-slate-700">{{ number_format($flock->initial_population, 0, ',', '.') }} Ekor</span>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div class="text-right">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Ayam Saat Ini</span>
                        <span class="font-extrabold text-maroon-800 text-sm sm:text-base">{{ number_format($flock->coops->sum('active_chickens'), 0, ',', '.') }} Ekor</span>
                    </div>
                </div>
            </div>

            <!-- Blok Kandang Cards Grid -->
            <div class="mt-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Daftar Blok Kandang</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    @foreach($flock->coops as $coop)
                        <div class="p-4 rounded-2xl border border-slate-200/80 bg-slate-50/50 hover:bg-white hover:border-maroon-200 transition-all space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-extrabold text-slate-800">{{ $coop->name }}</span>
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-bold bg-maroon-50 text-maroon-800 border border-maroon-200">
                                    Umur {{ $coop->chicken_age_weeks }} Mgg
                                </span>
                            </div>

                            <div class="space-y-1 text-xs">
                                <div class="flex justify-between text-slate-500">
                                    <span>Ayam Aktif:</span>
                                    <span class="font-extrabold text-slate-800">{{ number_format($coop->active_chickens, 0, ',', '.') }} Ekor</span>
                                </div>
                                <div class="flex justify-between text-slate-500">
                                    <span>Kapasitas:</span>
                                    <span class="font-semibold text-slate-700">{{ number_format($coop->capacity, 0, ',', '.') }} Ekor</span>
                                </div>
                                <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden mt-1.5">
                                    <div class="bg-maroon-800 h-full rounded-full" style="width: {{ min(100, round(($coop->active_chickens / max(1, $coop->capacity)) * 100)) }}%"></div>
                                </div>
                            </div>

                            <button onclick="openModalEditCoop({{ json_encode($coop) }})" class="w-full py-2 rounded-xl bg-white border border-slate-200 hover:border-maroon-800 hover:text-maroon-800 text-slate-700 font-bold text-xs flex items-center justify-center gap-1.5 shadow-xs transition-all active:scale-95">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                <span>Edit Data Blok</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

</div>

<!-- MODAL EDIT DATA COOP / BLOK -->
<div id="modalEditCoop" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm opacity-0 invisible pointer-events-none transition-all duration-300 flex items-end sm:items-center justify-center p-0 sm:p-4">
    <div class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl transform translate-y-full sm:translate-y-0 transition-transform duration-300">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h3 class="font-extrabold text-slate-800 text-sm sm:text-base">Edit Data Blok Kandang</h3>
            <button onclick="closeModalEditCoop()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form id="formEditCoop" method="POST" action="" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nama Blok *</label>
                <input type="text" id="editCoopName" name="name" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-800">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Ayam Aktif (Ekor) *</label>
                    <input type="number" id="editCoopActive" name="active_chickens" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-maroon-800">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kapasitas Maksimal *</label>
                    <input type="number" id="editCoopCapacity" name="capacity" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-700">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Umur Ayam (Minggu) *</label>
                <input type="number" id="editCoopAge" name="chicken_age_weeks" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-700">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-xl bg-maroon-800 hover:bg-maroon-900 text-white font-bold text-sm shadow-md transition-all active:scale-98">
                    Simpan Perubahan Blok
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openModalEditCoop(coop) {
        document.getElementById('formEditCoop').action = `/master/coops/${coop.id}/update`;
        document.getElementById('editCoopName').value = coop.name;
        document.getElementById('editCoopActive').value = coop.active_chickens;
        document.getElementById('editCoopCapacity').value = coop.capacity;
        document.getElementById('editCoopAge').value = coop.chicken_age_weeks;

        const modal = document.getElementById('modalEditCoop');
        const content = modal.querySelector('div');
        modal.classList.add('modal-active');
        content.classList.add('modal-content-active');
    }

    function closeModalEditCoop() {
        const modal = document.getElementById('modalEditCoop');
        const content = modal.querySelector('div');
        modal.classList.remove('modal-active');
        content.classList.remove('modal-content-active');
    }
</script>
@endpush
@endsection
