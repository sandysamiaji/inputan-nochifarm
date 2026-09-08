<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EggProduction;
use App\Models\FeedConsumption;
use App\Models\Mortality;
use App\Models\WeightSample;
use App\Models\HealthTreatment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RekapController extends Controller
{
    /**
     * Helper konversi tanggal Indonesia
     */
    private function formatIndoDate($date)
    {
        if (!$date) return '-';
        $carbon = Carbon::parse($date);
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return "{$carbon->day} " . ($bulan[$carbon->month] ?? $carbon->format('M')) . " {$carbon->year}";
    }

    /**
     * Menentukan rentang tanggal berdasarkan input atau preset
     */
    private function resolveDateRange(Request $request)
    {
        $preset = $request->query('preset');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $today = Carbon::today();

        if ($preset) {
            switch ($preset) {
                case 'hari_ini':
                    $startDate = $today->toDateString();
                    $endDate = $today->toDateString();
                    break;
                case 'kemarin':
                    $yesterday = $today->copy()->subDay();
                    $startDate = $yesterday->toDateString();
                    $endDate = $yesterday->toDateString();
                    break;
                case '7_hari':
                    $startDate = $today->copy()->subDays(6)->toDateString();
                    $endDate = $today->toDateString();
                    break;
                case '30_hari':
                    $startDate = $today->copy()->subDays(29)->toDateString();
                    $endDate = $today->toDateString();
                    break;
                case 'bulan_ini':
                    $startDate = $today->copy()->startOfMonth()->toDateString();
                    $endDate = $today->copy()->endOfMonth()->toDateString();
                    break;
                case 'bulan_lalu':
                    $lastMonth = $today->copy()->subMonth();
                    $startDate = $lastMonth->copy()->startOfMonth()->toDateString();
                    $endDate = $lastMonth->copy()->endOfMonth()->toDateString();
                    break;
            }
        }

        // Default ke periode mockup (10 Agustus 2026 s/d 18 Agustus 2026) jika belum ada input
        if (!$startDate || !$endDate) {
            // Cek apakah ada data di tanggal 10-18 Agt 2026
            $hasAugustData = EggProduction::whereBetween('date', ['2026-08-10', '2026-08-18'])->exists();
            if ($hasAugustData) {
                $startDate = '2026-08-10';
                $endDate = '2026-08-18';
                $preset = 'custom';
            } else {
                $startDate = $today->copy()->subDays(7)->toDateString();
                $endDate = $today->toDateString();
                $preset = '7_hari';
            }
        }

        // Pastikan start <= end
        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        return [$startDate, $endDate, $preset];
    }

    /**
     * 1. Halaman Utama Rekap Data (Filter, Ringkasan Metrik, dan Grafik Tren)
     */
    public function index(Request $request)
    {
        $user = Auth::user() ?? User::first();
        [$startDate, $endDate, $preset] = $this->resolveDateRange($request);

        $startCarbon = Carbon::parse($startDate);
        $endCarbon = Carbon::parse($endDate);

        $formattedRange = $this->formatIndoDate($startDate) . ' - ' . $this->formatIndoDate($endDate);

        // 1. Metrik Produksi Telur
        $eggQuery = EggProduction::whereBetween('date', [$startDate, $endDate]);
        $totalTelurPeti = (float) $eggQuery->sum('crates_count');
        $totalTelurButir = (int) $eggQuery->sum('total_eggs');
        $totalTelurBroken = (int) $eggQuery->sum('broken_eggs');
        $totalTelurGood = (int) $eggQuery->sum('good_eggs');

        // 2. Metrik Pemakaian Pakan
        $feedQuery = FeedConsumption::whereBetween('date', [$startDate, $endDate]);
        $totalPakanKg = (float) $feedQuery->sum('quantity_kg');

        // 3. Metrik Mortalitas
        $mortalityQuery = Mortality::whereBetween('date', [$startDate, $endDate]);
        $totalMortalitas = (int) $mortalityQuery->sum('count');

        // 4. Metrik Berat Badan (Rata-rata)
        $weightQuery = WeightSample::whereBetween('date', [$startDate, $endDate]);
        $avgBobot = $weightQuery->avg('average_weight_kg');
        if (!$avgBobot) {
            $latestWeight = WeightSample::latest('date')->first();
            $avgBobot = $latestWeight ? (float) $latestWeight->average_weight_kg : 1.60;
        } else {
            $avgBobot = (float) $avgBobot;
        }

        // 5. Metrik Vaksin / Obat
        $healthQuery = HealthTreatment::whereBetween('date', [$startDate, $endDate]);
        $totalVaksinKegiatan = $healthQuery->count();

        // 6. Data Grafik Tren Harian (Line Chart)
        $chartLabels = [];
        $chartEggPeti = [];
        $chartEggButir = [];
        $chartFeedKg = [];
        $chartMortality = [];

        $diffDays = $startCarbon->diffInDays($endCarbon);
        // Batasi label maksimal 31 hari agar grafik tetap jernih
        $step = max(1, (int) ceil($diffDays / 31));

        $cursor = $startCarbon->copy();
        while ($cursor->lte($endCarbon)) {
            $curDate = $cursor->toDateString();
            
            // Format label e.g. "10 Agt" atau "10"
            $chartLabels[] = $cursor->format('d');

            $dailyEggs = EggProduction::whereDate('date', $curDate)->get();
            $chartEggPeti[] = (float) $dailyEggs->sum('crates_count');
            $chartEggButir[] = (int) $dailyEggs->sum('total_eggs');

            $dailyFeeds = FeedConsumption::whereDate('date', $curDate)->get();
            $chartFeedKg[] = (float) $dailyFeeds->sum('quantity_kg');

            $dailyMortality = Mortality::whereDate('date', $curDate)->get();
            $chartMortality[] = (int) $dailyMortality->sum('count');

            $cursor->addDays($step);
        }

        return view('rekap.index', compact(
            'user',
            'startDate', 'endDate', 'preset', 'formattedRange',
            'totalTelurPeti', 'totalTelurButir', 'totalTelurBroken', 'totalTelurGood',
            'totalPakanKg', 'totalMortalitas', 'avgBobot', 'totalVaksinKegiatan',
            'chartLabels', 'chartEggPeti', 'chartEggButir', 'chartFeedKg', 'chartMortality'
        ));
    }

    /**
     * 2. Halaman Rekap Detail (Tabel Lengkap & Sub-tabs)
     */
    public function detail(Request $request)
    {
        $user = Auth::user() ?? User::first();
        [$startDate, $endDate, $preset] = $this->resolveDateRange($request);
        $tab = $request->query('tab', 'produksi');

        $formattedRange = $this->formatIndoDate($startDate) . ' - ' . $this->formatIndoDate($endDate);

        // Data berdasarkan tab aktif
        $data = null;
        $summary = [];

        if ($tab === 'produksi') {
            // Group by date agar sesuai mockup layar 4
            $records = EggProduction::with('coop')
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'asc')
                ->get();

            // Aggregasi per tanggal
            $grouped = $records->groupBy(function ($item) {
                return $item->date->format('Y-m-d');
            });

            $tableRows = [];
            $totalPeti = 0;
            $totalButir = 0;
            $totalBroken = 0;

            foreach ($grouped as $d => $group) {
                $p = (float) $group->sum('crates_count');
                $b = (int) $group->sum('total_eggs');
                $br = (int) $group->sum('broken_eggs');
                $tableRows[] = [
                    'date' => $d,
                    'formatted_date' => $this->formatIndoDate($d),
                    'peti' => $p,
                    'butir' => $b,
                    'broken' => $br,
                    'good' => $b - $br,
                ];
                $totalPeti += $p;
                $totalButir += $b;
                $totalBroken += $br;
            }

            $data = $tableRows;
            $summary = [
                'total_peti' => $totalPeti,
                'total_butir' => $totalButir,
                'total_broken' => $totalBroken,
            ];

        } elseif ($tab === 'pakan') {
            $data = FeedConsumption::with('coop', 'user')
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->get();

            $summary = [
                'total_kg' => (float) $data->sum('quantity_kg'),
                'total_records' => $data->count(),
            ];

        } elseif ($tab === 'mortalitas') {
            $data = Mortality::with('coop', 'user')
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->get();

            $summary = [
                'total_ekor' => (int) $data->sum('count'),
                'total_records' => $data->count(),
            ];

        } elseif ($tab === 'bobot') {
            $data = WeightSample::with('coop', 'user')
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get();

            $summary = [
                'avg_bobot' => (float) $data->avg('average_weight_kg'),
                'avg_keseragaman' => (float) $data->avg('uniformity_percentage'),
                'total_records' => $data->count(),
            ];

        } elseif ($tab === 'vaksin') {
            $data = HealthTreatment::with('coop', 'user')
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->get();

            $summary = [
                'total_kegiatan' => $data->count(),
            ];
        }

        return view('rekap.detail', compact(
            'user', 'startDate', 'endDate', 'preset', 'formattedRange', 'tab', 'data', 'summary'
        ));
    }

    /**
     * 3. Export Data Rekap ke Excel (CSV UTF-8 dengan BOM)
     */
    public function exportExcel(Request $request)
    {
        [$startDate, $endDate] = $this->resolveDateRange($request);
        $tab = $request->query('tab', 'produksi');

        $filename = "rekap_{$tab}_{$startDate}_{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($startDate, $endDate, $tab) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM agar Excel membacanya dengan rapi tanpa masalah encoding
            fputs($file, "\xEF\xBB\xBF");

            if ($tab === 'produksi') {
                fputcsv($file, ['Tanggal', 'Produksi Telur (Peti)', 'Produksi Telur (Butir)', 'Telur Retak (Butir)', 'Telur Utuh (Butir)']);
                
                $records = EggProduction::whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date', 'asc')
                    ->get()
                    ->groupBy(function ($item) { return $item->date->format('Y-m-d'); });

                $totalPeti = 0; $totalButir = 0; $totalBroken = 0;
                foreach ($records as $d => $group) {
                    $p = (float) $group->sum('crates_count');
                    $b = (int) $group->sum('total_eggs');
                    $br = (int) $group->sum('broken_eggs');
                    fputcsv($file, [
                        $this->formatIndoDate($d),
                        number_format($p, 0, ',', '.'),
                        number_format($b, 0, ',', '.'),
                        number_format($br, 0, ',', '.'),
                        number_format($b - $br, 0, ',', '.')
                    ]);
                    $totalPeti += $p; $totalButir += $b; $totalBroken += $br;
                }
                fputcsv($file, [
                    'Total',
                    number_format($totalPeti, 0, ',', '.'),
                    number_format($totalButir, 0, ',', '.'),
                    number_format($totalBroken, 0, ',', '.'),
                    number_format($totalButir - $totalBroken, 0, ',', '.')
                ]);

            } elseif ($tab === 'pakan') {
                fputcsv($file, ['Tanggal', 'Waktu', 'Waktu Pakan', 'Nama Pakan', 'Jumlah (Kg)', 'Kandang', 'Catatan']);
                $records = FeedConsumption::with('coop')->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                $totalKg = 0;
                foreach ($records as $r) {
                    fputcsv($file, [
                        $this->formatIndoDate($r->date),
                        $r->time ? substr($r->time, 0, 5) : '-',
                        $r->feeding_time ?? '-',
                        $r->feed_name,
                        number_format($r->quantity_kg, 2, ',', '.'),
                        $r->coop ? $r->coop->name : '-',
                        $r->notes ?? '-'
                    ]);
                    $totalKg += $r->quantity_kg;
                }
                fputcsv($file, ['Total', '', '', '', number_format($totalKg, 2, ',', '.'), '', '']);

            } elseif ($tab === 'mortalitas') {
                fputcsv($file, ['Tanggal', 'Waktu', 'Kematian (Ekor)', 'Penyebab', 'Kandang', 'Catatan']);
                $records = Mortality::with('coop')->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                $totalEkor = 0;
                foreach ($records as $r) {
                    fputcsv($file, [
                        $this->formatIndoDate($r->date),
                        $r->time ? substr($r->time, 0, 5) : '-',
                        $r->count,
                        $r->cause ?? '-',
                        $r->coop ? $r->coop->name : '-',
                        $r->notes ?? '-'
                    ]);
                    $totalEkor += $r->count;
                }
                fputcsv($file, ['Total', '', $totalEkor, '', '', '']);

            } elseif ($tab === 'bobot') {
                fputcsv($file, ['Tanggal', 'Bobot Rata-rata (Kg)', 'Jumlah Sampel (Ekor)', 'Keseragaman (%)', 'Umur (Minggu)', 'Catatan']);
                $records = WeightSample::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                foreach ($records as $r) {
                    fputcsv($file, [
                        $this->formatIndoDate($r->date),
                        number_format($r->average_weight_kg, 3, ',', '.'),
                        $r->sample_count,
                        number_format($r->uniformity_percentage, 1, ',', '.') . '%',
                        $r->age_weeks . ' Minggu',
                        $r->notes ?? '-'
                    ]);
                }

            } elseif ($tab === 'vaksin') {
                fputcsv($file, ['Tanggal', 'Waktu', 'Jenis', 'Nama Obat / Vaksin', 'Dosis', 'Metode', 'Catatan']);
                $records = HealthTreatment::whereBetween('date', [$startDate, $endDate])->orderBy('date', 'asc')->get();
                foreach ($records as $r) {
                    fputcsv($file, [
                        $this->formatIndoDate($r->date),
                        $r->time ? substr($r->time, 0, 5) : '-',
                        ucfirst($r->type),
                        $r->medicine_name,
                        $r->dosage ?? '-',
                        $r->application_method ?? '-',
                        $r->notes ?? '-'
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
