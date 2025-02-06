<?php
namespace App\Http\Controllers;

use App\Models\Master_Barang;
use App\Models\Satuan_Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MasterBarangController extends Controller
{
    public function store(Request $request)
    
    {
        //Cek apakah data yang diterima sudah benar
        Log::info('Incoming Request:', $request->all());
//return $request->all();
        // Validate the incoming request data
        $validatedData = $request->validate([
            '*.nama_barang' => 'required|string',
            '*.img_url' => 'required|url',
            '*.qty' => 'required|integer',
            '*.status' => 'required|boolean',
            '*.satuan' => 'required|array', // Ensure that 'satuan' is an array
            '*.satuan.*.nama_satuan' => 'required|string',
            '*.satuan.*.harga' => 'required|numeric',
            '*.satuan.*.status' => 'required|boolean',
        ]);
            // Loop untuk menyimpan data Master_Barang dan Satuan_Barang
    $masterBarangs= [];
        
    foreach ($validatedData as $barangData) {
        // Simpan data ke tabel master_barang
        $masterBarang = Master_Barang::create([
            'nama_barang' => $barangData['nama_barang'],
            'img_url' => $barangData['img_url'],
            'qty' => $barangData['qty'],
            'status' => $barangData['status'],
        ]);

        $masterbarangId = $masterBarang->id;


        // Loop buat menyimpan data satuan_barang
            foreach ($barangData['satuan'] as $satuanData) {
                Satuan_Barang::create([
                    'barang_id' => $masterbarangId,
                    'nama_satuan' => $satuanData['nama_satuan'],
                    'harga' => $satuanData['harga'],
                    'status' => $satuanData['status'],
                ]);


        $masterBarangs[] = $masterBarang; // Simpan masterBarang untuk respons

        // Kembalikan response dengan data yang telah disimpan
    
}
    return response()->json([
         'message' => 'Data berhasil disimpan',
        'master_barang' => $masterBarangs
    ], 201);
    }
}
        public function index()
    {
        // Buat ambil semua data Master_Barang beserta satuan terkait
        $masterBarangs = Master_Barang::with('satuan')->get();

        // Mengembalikan response dengan semua data
        return response()->json($masterBarangs, 200);
    }
}

