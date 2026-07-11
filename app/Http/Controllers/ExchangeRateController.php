<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

/**
 * NILAI TAMBAH: Integrasi ke API Publik (Open Exchange Rate - open.er-api.com).
 * Menampilkan kurs mata uang asing terhadap Rupiah secara real-time, berguna
 * untuk membantu estimasi nilai barang impor pada aplikasi inventaris ini.
 * API yang dipakai gratis & tidak memerlukan API key.
 */
class ExchangeRateController extends Controller
{
    /**
     * Halaman Kurs Mata Uang.
     */
    public function index()
    {
        return view('kurs.index');
    }

    /**
     * AJAX: Ambil data kurs terbaru dari API publik.
     */
    public function getData()
    {
        try {
            $response = Http::timeout(8)->get('https://open.er-api.com/v6/latest/USD');

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data kurs dari API eksternal.',
                ], 502);
            }

            $body = $response->json();
            $rates = $body['rates'] ?? [];

            $mataUangDipantau = ['IDR', 'USD', 'EUR', 'JPY', 'SGD', 'CNY', 'MYR'];
            $hasil = [];

            foreach ($mataUangDipantau as $kode) {
                if (isset($rates[$kode])) {
                    $hasil[] = [
                        'kode' => $kode,
                        'kurs_terhadap_usd' => $rates[$kode],
                        // Perkiraan nilai 1 unit mata uang dalam Rupiah (basis kurs USD -> IDR).
                        'nilai_dalam_rupiah' => isset($rates['IDR'])
                            ? round($rates['IDR'] / $rates[$kode], 2)
                            : null,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'base' => $body['base_code'] ?? 'USD',
                'last_update' => $body['time_last_update_utc'] ?? null,
                'data' => $hasil,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghubungi layanan kurs eksternal. Coba lagi nanti.',
            ], 500);
        }
    }
}
