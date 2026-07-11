<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

/**
 * MODUL DATA MASTER: Kategori Barang.
 * Mengikuti pola yang sama persis dengan ItemController (AJAX CRUD + Live Search)
 * supaya konsisten dengan struktur project yang sudah ada.
 */
class CategoryController extends Controller
{
    /**
     * Halaman Data Master - Kategori (Admin).
     */
    public function index()
    {
        return view('master.kategori');
    }

    /**
     * AJAX: Ambil seluruh data kategori, dengan dukungan Live Search.
     */
    public function getData(Request $request)
    {
        $keyword = $request->query('search');

        $categories = Category::when($keyword, function ($query, $keyword) {
                $query->where('nama_kategori', 'like', "%{$keyword}%")
                      ->orWhere('kode_kategori', 'like', "%{$keyword}%");
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * AJAX: Simpan kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan!',
            'data' => $category,
        ], 201);
    }

    /**
     * AJAX: Ambil data satu kategori (untuk mengisi form Edit pada modal).
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }

    /**
     * AJAX: Update data kategori.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255'
        ]);

        $category->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui!',
            'data' => $category,
        ]);
    }

    /**
     * AJAX: Hapus data kategori.
     */
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus!',
        ]);
    }
}
