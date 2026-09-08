<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FarmStock;
use App\Models\User;
use Carbon\Carbon;

class WarehouseSeeder extends Seeder
{
    /**
     * Seed warehouse stock transactions matching mockup data.
     */
    public function run(): void
    {
        $petugas = User::where('username', 'petugas')->first() ?? User::first();
        $petugasId = $petugas ? $petugas->id : 1;

        $supervisor = User::where('username', 'supervisor')->first() ?? $petugas;
        $supervisorId = $supervisor ? $supervisor->id : $petugasId;

        // Hanya isi jika tabel farm_stocks masih kosong
        if (FarmStock::count() === 0) {
            
            // =========================
            // 1. GUDANG TELUR
            // Target: Masuk 2.480 Peti, Keluar 2.120 Peti, Stok 360 Peti
            // =========================
            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-29',
                'category' => 'telur',
                'item_name' => 'Produksi Telur',
                'type' => 'masuk',
                'quantity' => 2480,
                'unit' => 'Peti',
                'source' => 'A1, A2, A3',
                'notes' => 'Produksi harian pagi (62.000 Butir)',
                'created_at' => Carbon::parse('2025-05-29 06:30:00'),
                'updated_at' => Carbon::parse('2025-05-29 06:30:00'),
            ]);

            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-29',
                'category' => 'telur',
                'item_name' => 'Penjualan Telur',
                'type' => 'keluar',
                'quantity' => 120,
                'unit' => 'Peti',
                'source' => 'Pelanggan Langganan',
                'notes' => 'Penjualan harian (3.000 Butir)',
                'created_at' => Carbon::parse('2025-05-29 08:15:00'),
                'updated_at' => Carbon::parse('2025-05-29 08:15:00'),
            ]);

            FarmStock::create([
                'user_id' => $supervisorId,
                'date' => '2025-05-28',
                'category' => 'telur',
                'item_name' => 'Penjualan Telur',
                'type' => 'keluar',
                'quantity' => 80,
                'unit' => 'Peti',
                'source' => 'Toko Sembako Ritel',
                'notes' => 'Penjualan sore (2.000 Butir)',
                'created_at' => Carbon::parse('2025-05-28 14:20:00'),
                'updated_at' => Carbon::parse('2025-05-28 14:20:00'),
            ]);

            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-27',
                'category' => 'telur',
                'item_name' => 'Penjualan Telur Partai Besar',
                'type' => 'keluar',
                'quantity' => 1920,
                'unit' => 'Peti',
                'source' => 'Distributor Telur Utama',
                'notes' => 'Kirim ke distributor luar kota',
                'created_at' => Carbon::parse('2025-05-27 10:00:00'),
                'updated_at' => Carbon::parse('2025-05-27 10:00:00'),
            ]);


            // =========================
            // 2. GUDANG PAKAN
            // Target: Masuk 18.250 Kg, Keluar 12.450 Kg, Stok 5.800 Kg
            // =========================
            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-29',
                'category' => 'pakan',
                'item_name' => 'Pembelian Pakan',
                'type' => 'masuk',
                'quantity' => 5000,
                'unit' => 'Kg',
                'source' => 'PT Feedmill Indonesia',
                'notes' => 'Pakan Layer Dewasa (100 Karung @50 Kg)',
                'created_at' => Carbon::parse('2025-05-29 10:00:00'),
                'updated_at' => Carbon::parse('2025-05-29 10:00:00'),
            ]);

            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-29',
                'category' => 'pakan',
                'item_name' => 'Pemakaian Pakan',
                'type' => 'keluar',
                'quantity' => 1820,
                'unit' => 'Kg',
                'source' => 'A1, A2, A3',
                'notes' => 'Pemakaian pagi hari (Pakan Layer)',
                'created_at' => Carbon::parse('2025-05-29 07:10:00'),
                'updated_at' => Carbon::parse('2025-05-29 07:10:00'),
            ]);

            FarmStock::create([
                'user_id' => $supervisorId,
                'date' => '2025-05-28',
                'category' => 'pakan',
                'item_name' => 'Pembelian Pakan',
                'type' => 'masuk',
                'quantity' => 4500,
                'unit' => 'Kg',
                'source' => 'CV Mitra Pakan Unggas',
                'notes' => 'Pakan Konsentrat Layer',
                'created_at' => Carbon::parse('2025-05-28 11:20:00'),
                'updated_at' => Carbon::parse('2025-05-28 11:20:00'),
            ]);

            FarmStock::create([
                'user_id' => $supervisorId,
                'date' => '2025-05-27',
                'category' => 'pakan',
                'item_name' => 'Pemakaian Pakan',
                'type' => 'keluar',
                'quantity' => 1750,
                'unit' => 'Kg',
                'source' => 'Kandang B & C',
                'notes' => 'Pemakaian pakan sore dan pagi',
                'created_at' => Carbon::parse('2025-05-27 09:15:00'),
                'updated_at' => Carbon::parse('2025-05-27 09:15:00'),
            ]);

            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-20',
                'category' => 'pakan',
                'item_name' => 'Stok Awal Pakan Prower & Starter',
                'type' => 'masuk',
                'quantity' => 8750,
                'unit' => 'Kg',
                'source' => 'Gudang Suplai Pusat',
                'notes' => 'Saldo awal stok pakan komplit',
                'created_at' => Carbon::parse('2025-05-20 08:00:00'),
                'updated_at' => Carbon::parse('2025-05-20 08:00:00'),
            ]);

            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-26',
                'category' => 'pakan',
                'item_name' => 'Pemakaian Pakan Kumulatif Pekan Lalu',
                'type' => 'keluar',
                'quantity' => 8880,
                'unit' => 'Kg',
                'source' => 'Kandang Seluruh Blok',
                'notes' => 'Konsumsi pakan rutin periode lalu',
                'created_at' => Carbon::parse('2025-05-26 17:00:00'),
                'updated_at' => Carbon::parse('2025-05-26 17:00:00'),
            ]);


            // =========================
            // 3. GUDANG OBAT, VAKSIN & VITAMIN
            // Target: Masuk 48 Item, Keluar 27 Item, Stok 21 Item
            // =========================
            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-29',
                'category' => 'obat',
                'item_name' => 'Pembelian Obat Vitamin B Complex',
                'type' => 'masuk',
                'quantity' => 10,
                'unit' => 'Botol',
                'source' => 'Apotek Hewan Sejahtera',
                'notes' => 'Vitamin B Complex 100ml suplemen harian',
                'created_at' => Carbon::parse('2025-05-29 09:30:00'),
                'updated_at' => Carbon::parse('2025-05-29 09:30:00'),
            ]);

            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-29',
                'category' => 'obat',
                'item_name' => 'Pemakaian Obat Vitamin B Complex',
                'type' => 'keluar',
                'quantity' => 2,
                'unit' => 'Botol',
                'source' => 'Kandang Blok A & B',
                'notes' => 'Campuran air minum pagi 2 botol',
                'created_at' => Carbon::parse('2025-05-29 07:40:00'),
                'updated_at' => Carbon::parse('2025-05-29 07:40:00'),
            ]);

            FarmStock::create([
                'user_id' => $supervisorId,
                'date' => '2025-05-28',
                'category' => 'vaksin',
                'item_name' => 'Pembelian Vaksin ND IB Vaccine',
                'type' => 'masuk',
                'quantity' => 5,
                'unit' => 'Botol',
                'source' => 'Distributor Medivac',
                'notes' => 'ND IB 1000 dosis vaksin tetes mata',
                'created_at' => Carbon::parse('2025-05-28 10:15:00'),
                'updated_at' => Carbon::parse('2025-05-28 10:15:00'),
            ]);

            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-28',
                'category' => 'vaksin',
                'item_name' => 'Pemakaian Vaksin ND IB Vaccine',
                'type' => 'keluar',
                'quantity' => 1,
                'unit' => 'Botol',
                'source' => 'Kandang Blok A1',
                'notes' => 'Vaksinasi booster ayam layer',
                'created_at' => Carbon::parse('2025-05-28 08:00:00'),
                'updated_at' => Carbon::parse('2025-05-28 08:00:00'),
            ]);

            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-15',
                'category' => 'obat',
                'item_name' => 'Stok Awal Obat & Vitamin Pelengkap',
                'type' => 'masuk',
                'quantity' => 33,
                'unit' => 'Item',
                'source' => 'Gudang Logistik Pusat',
                'notes' => 'Disinfektan, Elektrolit, Mineral & Antibiotik',
                'created_at' => Carbon::parse('2025-05-15 08:00:00'),
                'updated_at' => Carbon::parse('2025-05-15 08:00:00'),
            ]);

            FarmStock::create([
                'user_id' => $petugasId,
                'date' => '2025-05-26',
                'category' => 'obat',
                'item_name' => 'Pemakaian Rutin Disinfektan & Vitamin',
                'type' => 'keluar',
                'quantity' => 24,
                'unit' => 'Item',
                'source' => 'Semua Blok Kandang',
                'notes' => 'Disinfeksi kandang dan vitamin mingguan',
                'created_at' => Carbon::parse('2025-05-26 16:00:00'),
                'updated_at' => Carbon::parse('2025-05-26 16:00:00'),
            ]);
        }
    }
}
