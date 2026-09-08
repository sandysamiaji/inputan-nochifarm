<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EggProduction;
use App\Models\FeedConsumption;
use App\Models\Mortality;
use App\Models\WeightSample;
use App\Models\HealthTreatment;
use App\Models\Flock;
use App\Models\Coop;
use App\Models\User;
use Carbon\Carbon;

class RekapSeeder extends Seeder
{
    /**
     * Run database seeds for Rekap period (10 - 18 Agustus 2026) matching mockups.
     */
    public function run(): void
    {
        $petugas = User::where('username', 'petugas')->first() ?? User::first();
        $userId = $petugas ? $petugas->id : 1;

        $flock = Flock::first() ?? Flock::create([
            'name' => 'Klotter 1',
            'code' => 'K1',
            'start_date' => '2026-03-01',
            'initial_population' => 2400,
            'current_population' => 2270,
            'breed' => 'Lohmann Brown',
            'is_active' => true,
        ]);

        $coop = Coop::first() ?? Coop::create([
            'flock_id' => $flock->id,
            'name' => 'Blok A',
            'code' => 'A',
            'capacity' => 800,
            'active_chickens' => 762,
            'chicken_age_weeks' => 21,
            'is_active' => true,
        ]);

        // Cek apakah data telur untuk periode 10-18 Agustus 2026 sudah ada
        if (EggProduction::whereBetween('date', ['2026-08-10', '2026-08-18'])->count() === 0) {
            
            // 9 Hari: 10 Agt s/d 18 Agt 2026
            // Data persis di tabel mockup: Total 2.480 Peti, 62.000 Butir
            $dailyEggs = [
                ['date' => '2026-08-10', 'peti' => 280, 'butir' => 7000, 'broken' => 25],
                ['date' => '2026-08-11', 'peti' => 275, 'butir' => 6875, 'broken' => 20],
                ['date' => '2026-08-12', 'peti' => 290, 'butir' => 7250, 'broken' => 30],
                ['date' => '2026-08-13', 'peti' => 300, 'butir' => 7500, 'broken' => 35],
                ['date' => '2026-08-14', 'peti' => 285, 'butir' => 7125, 'broken' => 28],
                ['date' => '2026-08-15', 'peti' => 295, 'butir' => 7375, 'broken' => 26],
                ['date' => '2026-08-16', 'peti' => 275, 'butir' => 6875, 'broken' => 22],
                ['date' => '2026-08-17', 'peti' => 275, 'butir' => 6875, 'broken' => 20],
                ['date' => '2026-08-18', 'peti' => 285, 'butir' => 7125, 'broken' => 24],
            ];

            // Pakan: Total 17.250 Kg across 9 days (~1.916 Kg/hari)
            $dailyFeeds = [1920, 1910, 1930, 1950, 1900, 1925, 1890, 1910, 1915];

            // Mortalitas: Total 52 Ekor across 9 days
            $dailyMortality = [6, 5, 7, 6, 5, 8, 4, 5, 6];

            foreach ($dailyEggs as $idx => $item) {
                // 1. Egg Production
                EggProduction::create([
                    'flock_id' => $flock->id,
                    'coop_id' => $coop->id,
                    'user_id' => $userId,
                    'date' => $item['date'],
                    'time' => '06:30:00',
                    'crates_count' => $item['peti'],
                    'total_eggs' => $item['butir'],
                    'broken_eggs' => $item['broken'],
                    'good_eggs' => $item['butir'] - $item['broken'],
                    'notes' => 'Produksi harian telur Blok A, B, C',
                    'created_at' => Carbon::parse($item['date'] . ' 06:30:00'),
                    'updated_at' => Carbon::parse($item['date'] . ' 06:30:00'),
                ]);

                // 2. Feed Consumption
                FeedConsumption::create([
                    'flock_id' => $flock->id,
                    'coop_id' => $coop->id,
                    'user_id' => $userId,
                    'date' => $item['date'],
                    'time' => '07:15:00',
                    'feeding_time' => 'Pagi & Sore',
                    'feed_name' => 'Pakan Layer Dewasa',
                    'quantity_kg' => $dailyFeeds[$idx],
                    'notes' => 'Pemberian pakan rutin harian',
                    'created_at' => Carbon::parse($item['date'] . ' 07:15:00'),
                    'updated_at' => Carbon::parse($item['date'] . ' 07:15:00'),
                ]);

                // 3. Mortality
                Mortality::create([
                    'flock_id' => $flock->id,
                    'coop_id' => $coop->id,
                    'user_id' => $userId,
                    'date' => $item['date'],
                    'time' => '08:00:00',
                    'count' => $dailyMortality[$idx],
                    'type' => 'mati',
                    'cause' => 'Kematian wajar / seleksi',
                    'notes' => 'Pemeriksaan pagi hari',
                    'created_at' => Carbon::parse($item['date'] . ' 08:00:00'),
                    'updated_at' => Carbon::parse($item['date'] . ' 08:00:00'),
                ]);
            }

            // 4. Weight Samples (Bobot rata-rata 1.60 Kg)
            WeightSample::create([
                'flock_id' => $flock->id,
                'coop_id' => $coop->id,
                'user_id' => $userId,
                'date' => '2026-08-14',
                'sample_count' => 50,
                'average_weight_kg' => 1.600,
                'uniformity_percentage' => 89.2,
                'age_weeks' => 24,
                'notes' => 'Sampling timbang bobot mingguan',
                'created_at' => Carbon::parse('2026-08-14 09:00:00'),
                'updated_at' => Carbon::parse('2026-08-14 09:00:00'),
            ]);

            // 5. Health Treatments (2 Kegiatan)
            HealthTreatment::create([
                'flock_id' => $flock->id,
                'coop_id' => $coop->id,
                'user_id' => $userId,
                'date' => '2026-08-12',
                'time' => '08:30:00',
                'type' => 'vitamin',
                'medicine_name' => 'Vitamin B Complex',
                'dosage' => '5 Botol (500 ml)',
                'application_method' => 'Air Minum',
                'notes' => 'Suplemen daya tahan tubuh ayam layer',
                'created_at' => Carbon::parse('2026-08-12 08:30:00'),
                'updated_at' => Carbon::parse('2026-08-12 08:30:00'),
            ]);

            HealthTreatment::create([
                'flock_id' => $flock->id,
                'coop_id' => $coop->id,
                'user_id' => $userId,
                'date' => '2026-08-16',
                'time' => '09:00:00',
                'type' => 'vaksin',
                'medicine_name' => 'ND IB Vaccine Booster',
                'dosage' => '2 Botol (2000 dosis)',
                'application_method' => 'Tetes Mata',
                'notes' => 'Vaksinasi berkala pencegahan Newcastle Disease',
                'created_at' => Carbon::parse('2026-08-16 09:00:00'),
                'updated_at' => Carbon::parse('2026-08-16 09:00:00'),
            ]);
        }
    }
}
