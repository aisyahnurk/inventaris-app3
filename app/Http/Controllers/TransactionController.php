<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * MODUL TRANSAKSI: Barang Masuk & Barang Keluar.
 * Setiap transaksi otomatis menyesuaikan stok pada tabel items (tanpa
 * mengubah struktur/isi ItemController & Item model yang sudah ada,
 * hanya memanggil method update() milik Model Item seperti biasa).
 */
class TransactionController extends Controller
{
    /**
     * Halaman Transaksi (Admin) - bisa input & lihat riwayat.
     */
    public function index()
    {
        $items = Item::orderBy('nama')->get();

        return view('transaksi.index', compact('items'));
    }

    /**
     * Halaman Transaksi (User) - read only, riwayat transaksi saja.
     */
    public function userIndex()
    {
        return view('transaksi.user_index');
    }

    /**
     * AJAX: Ambil seluruh riwayat transaksi, dengan dukungan Live Search & filter jenis.
     */
    public function getData(Request $request)
    {
        $keyword = $request->query('search');
        $jenis = $request->query('jenis');

        $transactions = Transaction::with(['item', 'user'])
            ->when($keyword, function ($query, $keyword) {
                $query->whereHas('item', function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                      ->orWhere('kode', 'like', "%{$keyword}%");
                });
            })
            ->when($jenis, function ($query, $jenis) {
                $query->where('jenis', $jenis);
            })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($trx) {
                return [
                    'id' => $trx->id,
                    'item_id' => $trx->item_id,
                    'item_nama' => optional($trx->item)->nama,
                    'item_kode' => optional($trx->item)->kode,
                    'jenis' => $trx->jenis,
                    'jumlah' => $trx->jumlah,
                    'keterangan' => $trx->keterangan,
                    'tanggal' => $trx->tanggal,
                    'user_nama' => optional($trx->user)->name,
                    'created_at' => $trx->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * AJAX: Simpan transaksi baru (Barang Masuk / Barang Keluar) sekaligus
     * menyesuaikan stok barang terkait.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'jenis' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
            'tanggal' => 'required|date',
        ]);

        try {
            $transaction = DB::transaction(function () use ($validated) {
                $item = Item::findOrFail($validated['item_id']);

                if ($validated['jenis'] === 'keluar' && $item->stok < $validated['jumlah']) {
                    throw ValidationException::withMessages([
                        'jumlah' => 'Stok tidak mencukupi. Sisa stok saat ini: ' . $item->stok,
                    ]);
                }

                $item->stok = $validated['jenis'] === 'masuk'
                    ? $item->stok + $validated['jumlah']
                    : $item->stok - $validated['jumlah'];
                $item->save();

                return Transaction::create([
                    ...$validated,
                    'user_id' => Auth::id(),
                ]);
            });
        } catch (ValidationException $e) {
            throw $e;
        }

        return response()->json([
            'success' => true,
            'message' => $validated['jenis'] === 'masuk'
                ? 'Transaksi barang masuk berhasil dicatat & stok diperbarui!'
                : 'Transaksi barang keluar berhasil dicatat & stok diperbarui!',
            'data' => $transaction,
        ], 201);
    }

    /**
     * AJAX: Hapus transaksi. Stok barang dikembalikan seperti sebelum
     * transaksi tersebut dibuat, agar data stok tetap konsisten.
     */
    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $transaction = Transaction::findOrFail($id);
            $item = Item::find($transaction->item_id);

            if ($item) {
                $item->stok = $transaction->jenis === 'masuk'
                    ? $item->stok - $transaction->jumlah
                    : $item->stok + $transaction->jumlah;
                $item->save();
            }

            $transaction->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus & stok dikembalikan seperti semula!',
        ]);
    }
}
