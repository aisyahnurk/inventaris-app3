<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Dashboard ADMIN - menampilkan halaman utama (data awal dimuat via AJAX).
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Halaman USER - read only, data juga dimuat via AJAX (dengan live search).
     */
    public function userIndex()
    {
        return view('user_index');
    }

    /**
     * AJAX: Ambil seluruh data barang, dengan dukungan parameter pencarian (Live Search).
     * Dipakai oleh Admin Dashboard dan halaman User.
     */
    public function getData(Request $request)
    {
        $keyword = $request->query('search');

        $items = Item::with('category') 
            ->when($keyword, function ($query, $keyword) {
                $query->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('kode', 'like', "%{$keyword}%");
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * AJAX: Simpan data barang baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:items,kode',
            'stok' => 'required|integer|min:0',
            'category_id' => 'required',
        ]);

        $item = Item::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan!',
            'data' => $item,
        ], 201);
    }

    /**
     * AJAX: Ambil data satu barang (untuk mengisi form Edit pada modal).
     */
    public function show($id)
    {
        $item = Item::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $item,
        ]);
    }

    /**
     * AJAX: Update data barang.
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:items,kode,' . $item->id,
            'stok' => 'required|integer|min:0',
            'category_id' => 'required',
        ]);

        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil diperbarui!',
            'data' => $item,
        ]);
    }

    /**
     * AJAX: Hapus data barang.
     */
    public function delete($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus!',
        ]);
    }

    public function scanDetail($kode_barang)
    {
        // Cari barang berdasarkan kode
        $item = Item::where('kode', $kode_barang)->firstOrFail();
        
        // Tampilkan ke halaman view khusus detail (kamu bisa buat file scan_detail.blade.php nanti)
        return view('scan_detail', compact('item'));
    }

    public function getCategories() {
        return response()->json(Category::all());
    }

    
}
