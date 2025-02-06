<?php

namespace App\Http\Controllers;

use App\Models\master_barang;
use App\Models\satuan_barang;
use Illuminate\Http\Request;

class MasterBarangController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data yang diterima
        $validatedData = $request->validate([
            '*.nama_barang' => 'required|string',
            '*.img_url' => 'required|url',
            '*.qty' => 'required|integer',
            '*.status' => 'required|boolean',
            '*.satuan' => 'required|array',  // pastikan 'satuan' adalah array
            '*.satuan.*.nama_satuan' => 'required|string',
            '*.satuan.*.harga' => 'required|numeric',
            '*.satuan.*.status' => 'required|boolean',
        ]);
          // Simpan data ke tabel MasterBarang
        $masterBarang = master_barang::create([
            'nama_barang' => $validatedData['nama_barang'],
            'img_url' => $validatedData['img_url'],
            'qty' => $validatedData['qty'],
            'status' => $validatedData['status'],
        ]);

        // Loop untuk menyimpan data satuan_barang
        foreach ($validatedData['satuan'] as $satuanData) {
            // Tambahkan id_barang ke satuan_barang
            $satuanData['id_barang'] = $masterBarang->id;
            satuan_barang::create($satuanData);
        }

        // Kembalikan response dengan data yang telah disimpan
        return response()->json([
            'master_barang' => $masterBarang,
            'satuan_barang' => $masterBarang->satuans,  // Mengambil relasi satuan_barang
        ], 201);
    }
}
