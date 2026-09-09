<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\EggProduction;
use App\Models\FeedConsumption;
use App\Models\FarmStock;
use Carbon\Carbon;

class OutboundIntegrationService
{
    /**
     * Ringkasan Barang Keluar Telur (Penjualan nochifram + Mutasi Gudang)
     */
    public static function getEggOutboundSummary($startDate = null, $endDate = null)
    {
        $queryPeti = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.category', 'telur')
            ->where('sale_items.unit', 'Peti');

        $queryKg = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.category', 'telur')
            ->where('sale_items.unit', 'Kg');

        $queryRevenue = DB::table('sales')
            ->where('category', 'telur');

        if ($startDate && $endDate) {
            $queryPeti->whereBetween('sales.date', [$startDate, $endDate]);
            $queryKg->whereBetween('sales.date', [$startDate, $endDate]);
            $queryRevenue->whereBetween('sales.date', [$startDate, $endDate]);
        }

        $petiSold = (float) $queryPeti->sum('sale_items.quantity');
        $kgSold = (float) $queryKg->sum('sale_items.quantity');
        $totalRevenue = (float) $queryRevenue->sum('sales.total_amount');
        $transactionCount = (int) $queryRevenue->count();

        // Mutasi keluar manual farm_stocks jika ada
        $manualKeluarQuery = FarmStock::where('category', 'telur')->where('type', 'keluar');
        if ($startDate && $endDate) {
            $manualKeluarQuery->whereBetween('date', [$startDate, $endDate]);
        }
        $manualKeluarPeti = (float) $manualKeluarQuery->sum('quantity');

        // Total produksi telur kandang (Barang Masuk)
        $prodQuery = EggProduction::query();
        if ($startDate && $endDate) {
            $prodQuery->whereBetween('date', [$startDate, $endDate]);
        }
        $totalProducedCrates = (float) $prodQuery->sum('crates_count');
        $totalProducedEggs = (int) $prodQuery->sum('total_eggs');
        $totalProducedWeightKg = (float) $prodQuery->sum('weight_kg');

        // Fallback jika EggProduction 0 tapi FarmStock ada catatan masuk telur
        $farmStockMasukQuery = FarmStock::where('category', 'telur')->where('type', 'masuk');
        if ($startDate && $endDate) {
            $farmStockMasukQuery->whereBetween('date', [$startDate, $endDate]);
        }
        $farmStockMasukPeti = (float) $farmStockMasukQuery->sum('quantity');
        if ($totalProducedCrates == 0 && $farmStockMasukPeti > 0) {
            $totalProducedCrates = $farmStockMasukPeti;
        }
        if ($totalProducedEggs == 0 && $totalProducedCrates > 0) {
            $totalProducedEggs = (int) ($totalProducedCrates * 250);
        } elseif ($totalProducedCrates > 0 && $totalProducedEggs < ($totalProducedCrates * 150)) {
            // Sinkronisasi jika data butir di DB belum dikonversi penuh dari jumlah peti (1 Peti = 250 Butir)
            $totalProducedEggs = (int) round($totalProducedCrates * 250);
        }

        // Bobot telur masuk (1 Peti = 15 Kg)
        $totalProducedKg = $totalProducedWeightKg > 0 ? $totalProducedWeightKg : round($totalProducedCrates * 15.0, 1);

        // Total telur keluar bersih
        $totalKeluarPeti = $petiSold + $manualKeluarPeti;
        $totalKeluarKg = $kgSold;
        $totalKeluarEggs = (int) (($totalKeluarPeti * 250) + ($totalKeluarKg * 16));

        // Stok saat ini (bisa minus / defisit jika penjualan melebihi stok masuk)
        $currentStockPeti = round($totalProducedCrates - $totalKeluarPeti, 1);
        $currentStockKgTotal = round($totalProducedKg - ($totalKeluarPeti * 15.0) - $totalKeluarKg, 1);
        $currentStockEggs = (int) round(($currentStockPeti * 250) - ($totalKeluarKg * 16));

        return [
            'peti_sold' => $petiSold,
            'kg_sold' => $kgSold,
            'total_revenue' => $totalRevenue,
            'transaction_count' => $transactionCount,
            'manual_keluar_peti' => $manualKeluarPeti,
            'total_keluar_peti' => $totalKeluarPeti,
            'total_keluar_kg' => $totalKeluarKg,
            'total_keluar_eggs' => $totalKeluarEggs,
            'total_produced_crates' => $totalProducedCrates,
            'total_produced_eggs' => $totalProducedEggs,
            'total_produced_kg' => $totalProducedKg,
            'current_stock_peti' => $currentStockPeti,
            'current_stock_kg_total' => $currentStockKgTotal,
            'current_stock_eggs' => $currentStockEggs,
        ];
    }

    /**
     * Ringkasan Barang Keluar Pakan (Penjualan nochifram + Konsumsi Ayam Kandang)
     */
    public static function getFeedOutboundSummary($startDate = null, $endDate = null)
    {
        // 1. Penjualan Pakan (Karung & Kg)
        $queryKarung = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.category', 'pakan')
            ->where('sale_items.unit', 'Karung');

        $queryKg = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.category', 'pakan')
            ->where('sale_items.unit', 'Kg');

        $queryRevenue = DB::table('sales')
            ->where('category', 'pakan');

        if ($startDate && $endDate) {
            $queryKarung->whereBetween('sales.date', [$startDate, $endDate]);
            $queryKg->whereBetween('sales.date', [$startDate, $endDate]);
            $queryRevenue->whereBetween('sales.date', [$startDate, $endDate]);
        }

        $karungSold = (float) $queryKarung->sum('sale_items.quantity');
        $kgSold = (float) $queryKg->sum('sale_items.quantity');
        $totalRevenue = (float) $queryRevenue->sum('sales.total_amount');

        // Asumsi standar industri peternakan: 1 Karung = 50 Kg
        $soldInKg = ($karungSold * 50.0) + $kgSold;

        // 2. Konsumsi Pakan oleh Ayam di Kandang
        $consQuery = FeedConsumption::query();
        if ($startDate && $endDate) {
            $consQuery->whereBetween('date', [$startDate, $endDate]);
        }
        $consumptionKg = (float) $consQuery->sum('quantity_kg');
        $consumptionKarung = round($consumptionKg / 50.0, 1);

        // 3. Pakan Masuk MURNI dari input riil FarmStock (tanpa hardcoded fake baseline)
        $stockMasukQuery = FarmStock::where('category', 'pakan')->where('type', 'masuk');
        if ($startDate && $endDate) {
            $stockMasukQuery->whereBetween('date', [$startDate, $endDate]);
        }
        $purchasedKg = (float) $stockMasukQuery->sum('quantity');
        $purchasedKarung = round($purchasedKg / 50.0, 1);

        // 4. Mutasi manual keluar di FarmStock jika ada
        $stockKeluarQuery = FarmStock::where('category', 'pakan')->where('type', 'keluar');
        if ($startDate && $endDate) {
            $stockKeluarQuery->whereBetween('date', [$startDate, $endDate]);
        }
        $manualKeluarKg = (float) $stockKeluarQuery->sum('quantity');

        // Total Pakan Keluar (Konsumsi Kandang + Penjualan Luar)
        if ($consumptionKg > 0 || $soldInKg > 0) {
            $totalKeluarKg = $consumptionKg + $soldInKg;
        } else {
            $totalKeluarKg = $manualKeluarKg > 0 ? $manualKeluarKg : 0.0;
        }
        
        $totalKeluarKarung = round($totalKeluarKg / 50.0, 1);

        // Sisa stok pakan (bisa minus / defisit jika belum ada input pakan masuk)
        $currentStockKg = round($purchasedKg - $totalKeluarKg, 1);
        $currentStockKarung = round($currentStockKg / 50.0, 1);

        return [
            'karung_sold' => $karungSold,
            'kg_sold' => $kgSold,
            'sold_in_kg' => $soldInKg,
            'total_revenue' => $totalRevenue,
            'consumption_kg' => $consumptionKg,
            'consumption_karung' => $consumptionKarung,
            'purchased_kg' => $purchasedKg,
            'purchased_karung' => $purchasedKarung,
            'total_keluar_kg' => $totalKeluarKg,
            'total_keluar_karung' => $totalKeluarKarung,
            'current_stock_kg' => $currentStockKg,
            'current_stock_karung' => $currentStockKarung,
        ];
    }

    /**
     * Ambil Riwayat Penjualan Barang Keluar dari tabel sales + sale_items
     */
    public static function getSalesTransactions($category = null, $startDate = null, $endDate = null, $limit = 50)
    {
        $query = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select(
                'sales.id as sale_id',
                'sales.invoice_no',
                'sales.date',
                'sales.category',
                'sales.customer_name',
                'sales.customer_phone',
                'sales.payment_method',
                'sales.payment_status',
                'sales.notes',
                'sales.created_at',
                'sale_items.item_name',
                'sale_items.unit',
                'sale_items.quantity',
                'sale_items.unit_price',
                'sale_items.total_price'
            )
            ->orderBy('sales.date', 'desc')
            ->orderBy('sales.id', 'desc');

        if ($category) {
            $query->where('sales.category', $category);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('sales.date', [$startDate, $endDate]);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Ambil Riwayat Distribusi Armada (Trips)
     */
    public static function getTripOutbounds($type = null, $limit = 10)
    {
        $query = DB::table('trips')
            ->select(
                'id',
                'trip_code',
                'date',
                'type',
                'vehicle',
                'route',
                'initial_peti',
                'initial_kg',
                'initial_weight',
                'peti_returned',
                'kiloan_returned',
                'status',
                'created_at'
            )
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
