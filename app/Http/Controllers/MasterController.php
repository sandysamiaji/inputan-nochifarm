<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Flock;
use App\Models\Coop;
use Carbon\Carbon;

class MasterController extends Controller
{
    /**
     * Helper untuk ambil setting dari DB
     */
    private function getSetting($key, $default = null)
    {
        $row = DB::table('settings')->where('key', $key)->first();
        return $row ? $row->value : $default;
    }

    /**
     * Helper untuk simpan setting ke DB
     */
    private function setSetting($key, $value)
    {
        $now = Carbon::now();
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $value, 'updated_at' => $now]
        );
    }

    /**
     * 1. Halaman Menu Utama Master (Sesuai Gambar Mockup 1)
     */
    public function index()
    {
        $flocks = Flock::with('coops')->where('is_active', true)->get();
        $coops = Coop::where('is_active', true)->get();
        $totalChickens = (int) $coops->sum('active_chickens');
        $avgAgeWeeks = (int) ($coops->avg('chicken_age_weeks') ?: 21);

        $farmName = $this->getSetting('farm_name', 'NOCHI FARM');
        $motivation = $this->getSetting('dashboard_motivation_message', 'Semangat bekerja dan tetap jaga kebersihan serta performa kandang hari ini!');

        return view('master.index', compact(
            'flocks', 'coops', 'totalChickens', 'avgAgeWeeks', 'farmName', 'motivation'
        ));
    }

    /**
     * 2. Halaman Info Farm (Pengaturan Informasi Umum Peternakan & Dashboard)
     */
    public function infoFarm()
    {
        $settings = [
            'farm_name' => $this->getSetting('farm_name', 'NOCHI FARM'),
            'farm_tagline' => $this->getSetting('farm_tagline', 'Peternak Telur Berkualitas'),
            'dashboard_motivation_message' => $this->getSetting('dashboard_motivation_message', 'Semangat bekerja dan tetap jaga kebersihan serta performa kandang hari ini!'),
            'dashboard_chicken_status_message' => $this->getSetting('dashboard_chicken_status_message', 'Kondisi ayam saat ini memasuki umur minggu ke-21 (Masa Awal Bertelur Produktif / Subur). Pastikan pencahayaan dan asupan kalsium optimal.'),
            'dashboard_info_schedule_start' => $this->getSetting('dashboard_info_schedule_start', '06:00'),
            'dashboard_info_schedule_end' => $this->getSetting('dashboard_info_schedule_end', '18:00'),
            'dashboard_info_active' => $this->getSetting('dashboard_info_active', '1'),
        ];

        $coops = Coop::where('is_active', true)->get();
        $avgAgeWeeks = (int) ($coops->avg('chicken_age_weeks') ?: 21);

        return view('master.info-farm', compact('settings', 'avgAgeWeeks'));
    }

    /**
     * Simpan pembaruan Info Farm
     */
    public function updateInfoFarm(Request $request)
    {
        $validated = $request->validate([
            'farm_name' => 'required|string|max:255',
            'farm_tagline' => 'nullable|string|max:255',
            'dashboard_motivation_message' => 'required|string|max:1000',
            'dashboard_chicken_status_message' => 'required|string|max:1000',
            'dashboard_info_schedule_start' => 'nullable|string',
            'dashboard_info_schedule_end' => 'nullable|string',
            'dashboard_info_active' => 'nullable|boolean',
        ]);

        $this->setSetting('farm_name', $validated['farm_name']);
        $this->setSetting('farm_tagline', $validated['farm_tagline'] ?? '');
        $this->setSetting('dashboard_motivation_message', $validated['dashboard_motivation_message']);
        $this->setSetting('dashboard_chicken_status_message', $validated['dashboard_chicken_status_message']);
        $this->setSetting('dashboard_info_schedule_start', $validated['dashboard_info_schedule_start'] ?? '06:00');
        $this->setSetting('dashboard_info_schedule_end', $validated['dashboard_info_schedule_end'] ?? '18:00');
        $this->setSetting('dashboard_info_active', $request->has('dashboard_info_active') ? '1' : '0');

        return back()->with('success', 'Data Info Farm dan pesan Dashboard berhasil diperbarui!');
    }

    /**
     * 3. Halaman Blok & Klotter (Flock)
     */
    public function flocks()
    {
        $flocks = Flock::with('coops')->get();
        $coops = Coop::with('flock')->get();

        return view('master.flocks', compact('flocks', 'coops'));
    }

    /**
     * Update data Coop / Blok
     */
    public function updateCoop(Request $request, $id)
    {
        $coop = Coop::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'active_chickens' => 'required|integer|min:0',
            'chicken_age_weeks' => 'required|integer|min:1',
        ]);

        $coop->update($validated);

        return back()->with('success', "Data {$coop->name} berhasil diperbarui!");
    }

    /**
     * 4. Halaman Standar Produksi & Pakan
     */
    public function standards()
    {
        $standards = [
            'standard_production_egg_crates' => $this->getSetting('standard_production_egg_crates', '850'),
            'standard_feed_gram_per_chicken' => $this->getSetting('standard_feed_gram_per_chicken', '115'),
            'standard_avg_weight_kg' => $this->getSetting('standard_avg_weight_kg', '1.62'),
            'standard_weight_tolerance' => $this->getSetting('standard_weight_tolerance', '0.05'),
        ];

        return view('master.standards', compact('standards'));
    }

    /**
     * Simpan standar
     */
    public function updateStandards(Request $request)
    {
        $validated = $request->validate([
            'standard_production_egg_crates' => 'required|numeric|min:1',
            'standard_feed_gram_per_chicken' => 'required|numeric|min:1',
            'standard_avg_weight_kg' => 'required|numeric|min:0.1',
            'standard_weight_tolerance' => 'required|numeric|min:0.01',
        ]);

        foreach ($validated as $k => $v) {
            $this->setSetting($k, (string) $v);
        }

        return back()->with('success', 'Standar operasional kandang berhasil disimpan!');
    }

    /**
     * 5. Halaman Standar BB
     */
    public function standardBB()
    {
        $standards = [
            'standard_avg_weight_kg' => $this->getSetting('standard_avg_weight_kg', '1.62'),
            'standard_weight_tolerance' => $this->getSetting('standard_weight_tolerance', '0.05'),
        ];

        return view('master.standards', compact('standards'));
    }

    /**
     * 6. Halaman Vaksin & Obat
     */
    public function medicines()
    {
        $medicines = [
            [
                'name' => 'ND IB Vaccine (Newcastle Disease & Infectious Bronchitis)',
                'category' => 'Vaksin',
                'dosage' => '1000 - 2000 Dosis per Botol',
                'application' => 'Tetes Mata / Air Minum',
                'schedule' => 'Umur 4, 16, 24, 40 Minggu',
                'notes' => 'Pencegahan virus tetelo dan radang pernapasan layer',
            ],
            [
                'name' => 'ND Lasota',
                'category' => 'Vaksin',
                'dosage' => '1 Botol per 1000 Ekor',
                'application' => 'Tetes Mata',
                'schedule' => 'Umur 18 - 20 Minggu (Booster)',
                'notes' => 'Vaksinasi booster menjelang masa puncak bertelur',
            ],
            [
                'name' => 'Vitamin B Complex + Elektrolit',
                'category' => 'Vitamin & Suplemen',
                'dosage' => '1 gram per 2 Liter Air Minum',
                'application' => 'Air Minum Pagi Hari',
                'schedule' => 'Rutin 2x Seminggu atau Saat Cuaca Panas',
                'notes' => 'Mencegah stres panas (heat stress) dan memacu nafsu makan',
            ],
            [
                'name' => 'Kalsium & Mineral Premix Layer',
                'category' => 'Mineral',
                'dosage' => '2 kg per 100 kg Pakan Konsentrat',
                'application' => 'Campuran Pakan Kering',
                'schedule' => 'Setiap hari selama fase bertelur',
                'notes' => 'Memperkuat cangkang telur agar tidak mudah retak',
            ],
            [
                'name' => 'Disinfektan Kandang (Glutaraldehyde & QAC)',
                'category' => 'Sanitasi',
                'dosage' => '10 ml per 5 Liter Air',
                'application' => 'Semprot / Fogging Lingkungan',
                'schedule' => '1x Seminggu saat kandang kosong atau sela lorong',
                'notes' => 'Sterilisasi bakteri dan virus pembawa penyakit unggas',
            ]
        ];

        return view('master.medicines', compact('medicines'));
    }

    /**
     * 7. Halaman Pengaturan Aplikasi
     */
    public function settings()
    {
        $settings = [
            'farm_name' => $this->getSetting('farm_name', 'NOCHI FARM'),
            'farm_tagline' => $this->getSetting('farm_tagline', 'Peternak Telur Berkualitas'),
        ];

        return view('master.settings', compact('settings'));
    }
}
