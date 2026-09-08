<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FarmStock;
use App\Models\User;
use App\Models\Coop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    /**
     * Helper untuk format tanggal Indonesia
     */
    private function formatIndoDate($date)
    {
        if (!$date) return '-';
        $carbon = Carbon::parse($date);
        $bulanIndonesia = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        return "{$carbon->day} " . ($bulanIndonesia[$carbon->month] ?? $carbon->format('M')) . " {$carbon->year}";
    }

    /**
     * 1. Halaman Utama Gudang (Overview)
     */
    public function index()
    {
        $user = Auth::user() ?? User::where('role', 'user')->orWhere('username', 'petugas')->first() ?? User::first();

        // 1. Gudang Telur (Satuan: Peti)
        $telurMasuk = (float) FarmStock::where('category', 'telur')->where('type', 'masuk')->sum('quantity');
        $telurKeluar = (float) FarmStock::where('category', 'telur')->where('type', 'keluar')->sum('quantity');
        $telurStok = max(0, $telurMasuk - $telurKeluar);

        // 2. Gudang Pakan (Satuan: Kg)
        $pakanMasuk = (float) FarmStock::where('category', 'pakan')->where('type', 'masuk')->sum('quantity');
        $pakanKeluar = (float) FarmStock::where('category', 'pakan')->where('type', 'keluar')->sum('quantity');
        $pakanStok = max(0, $pakanMasuk - $pakanKeluar);

        // 3. Gudang Obat, Vaksin & Vitamin (Satuan: Item / Botol)
        $obatMasuk = (float) FarmStock::whereIn('category', ['obat', 'vaksin', 'vitamin'])->where('type', 'masuk')->sum('quantity');
        $obatKeluar = (float) FarmStock::whereIn('category', ['obat', 'vaksin', 'vitamin'])->where('type', 'keluar')->sum('quantity');
        $obatStok = max(0, $obatMasuk - $obatKeluar);

        // Mutasi stok terbaru
        $recentTransactions = FarmStock::with('user')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('warehouse.index', compact(
            'user',
            'telurMasuk', 'telurKeluar', 'telurStok',
            'pakanMasuk', 'pakanKeluar', 'pakanStok',
            'obatMasuk', 'obatKeluar', 'obatStok',
            'recentTransactions'
        ));
    }

    /**
     * 2. Sub-halaman Gudang Telur
     */
    public function telur(Request $request)
    {
        $user = Auth::user() ?? User::first();
        $tab = $request->query('tab', 'semua');
        $search = $request->query('q');

        $query = FarmStock::with('user')->where('category', 'telur');

        if ($tab === 'masuk') {
            $query->where('type', 'masuk');
        } elseif ($tab === 'keluar') {
            $query->where('type', 'keluar');
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Ringkasan Telur
        $totalMasuk = (float) FarmStock::where('category', 'telur')->where('type', 'masuk')->sum('quantity');
        $totalKeluar = (float) FarmStock::where('category', 'telur')->where('type', 'keluar')->sum('quantity');
        $stokSaatIni = max(0, $totalMasuk - $totalKeluar);

        $coops = Coop::where('is_active', true)->get();

        return view('warehouse.telur', compact(
            'user', 'items', 'tab', 'search', 'totalMasuk', 'totalKeluar', 'stokSaatIni', 'coops'
        ));
    }

    /**
     * 3. Sub-halaman Gudang Pakan
     */
    public function pakan(Request $request)
    {
        $user = Auth::user() ?? User::first();
        $tab = $request->query('tab', 'semua');
        $search = $request->query('q');

        $query = FarmStock::with('user')->where('category', 'pakan');

        if ($tab === 'masuk') {
            $query->where('type', 'masuk');
        } elseif ($tab === 'keluar') {
            $query->where('type', 'keluar');
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Ringkasan Pakan
        $totalMasuk = (float) FarmStock::where('category', 'pakan')->where('type', 'masuk')->sum('quantity');
        $totalKeluar = (float) FarmStock::where('category', 'pakan')->where('type', 'keluar')->sum('quantity');
        $stokSaatIni = max(0, $totalMasuk - $totalKeluar);

        $coops = Coop::where('is_active', true)->get();

        return view('warehouse.pakan', compact(
            'user', 'items', 'tab', 'search', 'totalMasuk', 'totalKeluar', 'stokSaatIni', 'coops'
        ));
    }

    /**
     * 4. Sub-halaman Gudang Obat, Vaksin & Vitamin
     */
    public function obat(Request $request)
    {
        $user = Auth::user() ?? User::first();
        $tab = $request->query('tab', 'semua');
        $search = $request->query('q');

        $query = FarmStock::with('user')->whereIn('category', ['obat', 'vaksin', 'vitamin']);

        if ($tab === 'masuk') {
            $query->where('type', 'masuk');
        } elseif ($tab === 'keluar') {
            $query->where('type', 'keluar');
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('source', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Ringkasan Obat, Vaksin & Vitamin
        $totalMasuk = (float) FarmStock::whereIn('category', ['obat', 'vaksin', 'vitamin'])->where('type', 'masuk')->sum('quantity');
        $totalKeluar = (float) FarmStock::whereIn('category', ['obat', 'vaksin', 'vitamin'])->where('type', 'keluar')->sum('quantity');
        $stokSaatIni = max(0, $totalMasuk - $totalKeluar);

        $coops = Coop::where('is_active', true)->get();

        return view('warehouse.obat', compact(
            'user', 'items', 'tab', 'search', 'totalMasuk', 'totalKeluar', 'stokSaatIni', 'coops'
        ));
    }

    /**
     * Store transaksi stok baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:telur,pakan,obat,vaksin,vitamin',
            'type' => 'required|in:masuk,keluar',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'date' => 'required|date',
            'time' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user() ?? User::where('role', 'user')->orWhere('username', 'petugas')->first() ?? User::first();
        $userId = $user ? $user->id : null;

        $createdAt = Carbon::parse($validated['date']);
        if (!empty($validated['time'])) {
            $timeParts = explode(':', $validated['time']);
            $createdAt->setTime((int) ($timeParts[0] ?? 0), (int) ($timeParts[1] ?? 0));
        } else {
            $createdAt->setTime(Carbon::now()->hour, Carbon::now()->minute);
        }

        FarmStock::create([
            'user_id' => $userId,
            'date' => $validated['date'],
            'category' => $validated['category'],
            'item_name' => $validated['item_name'],
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'source' => $validated['source'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        $namaJenis = ucfirst($validated['category']);
        return back()->with('success', "Transaksi Gudang {$namaJenis} berhasil disimpan!");
    }

    /**
     * Update transaksi stok
     */
    public function update(Request $request, $id)
    {
        $stock = FarmStock::findOrFail($id);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'type' => 'required|in:masuk,keluar',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'date' => 'required|date',
            'time' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!empty($validated['time'])) {
            $createdAt = Carbon::parse($validated['date']);
            $timeParts = explode(':', $validated['time']);
            $createdAt->setTime((int) ($timeParts[0] ?? 0), (int) ($timeParts[1] ?? 0));
            $stock->created_at = $createdAt;
        }

        $stock->item_name = $validated['item_name'];
        $stock->type = $validated['type'];
        $stock->quantity = $validated['quantity'];
        $stock->unit = $validated['unit'];
        $stock->date = $validated['date'];
        $stock->source = $validated['source'] ?? null;
        $stock->notes = $validated['notes'] ?? null;
        $stock->save();

        return back()->with('success', 'Data transaksi berhasil diperbarui!');
    }

    /**
     * Toggle status aktif/nonaktif data secara aman tanpa mengubah skema DB
     */
    public function toggleStatus($id)
    {
        $stock = FarmStock::findOrFail($id);
        $currentNotes = $stock->notes ?? '';

        if (str_starts_with(trim($currentNotes), '[NONAKTIF]')) {
            $stock->notes = trim(substr(trim($currentNotes), strlen('[NONAKTIF]')));
            $msg = 'Data transaksi berhasil diaktifkan kembali.';
        } else {
            $stock->notes = '[NONAKTIF] ' . $currentNotes;
            $msg = 'Data transaksi berhasil dinonaktifkan.';
        }

        $stock->save();
        return back()->with('success', $msg);
    }

    /**
     * Hapus transaksi stok
     */
    public function destroy($id)
    {
        $stock = FarmStock::findOrFail($id);
        $category = $stock->category;
        $stock->delete();

        return back()->with('success', 'Data transaksi gudang berhasil dihapus!');
    }
}
