<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function dashboard()
    {
        // Menghitung total data dari masing-masing tabel
        $total_barang = Item::count();
        $total_kategori = Category::count(); 
        $barang_masuk = Transaction::where('jenis', 'masuk')->count();
        $barang_keluar = Transaction::where('jenis', 'keluar')->count();

        // Mengirim variabel perhitungan ke halaman view 'dashboard'
        return view('dashboard', compact('total_barang', 'total_kategori', 'barang_masuk', 'barang_keluar'));
    }
}
