<?php
namespace App\Http\Controllers;

use App\Models\Master_Barang;
use App\Models\Satuan_Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MasterBarangController extends Controller
{
    public function store(Request $request)
{
    try {
        // Log request data for debugging
        Log::info('Incoming Request:', $request->all());

        // Validate the incoming request data
        $validatedData = $request->validate([
            '*.nama_barang' => 'required|string|max:255',
            '*.img_url' => 'required|url',
            '*.qty' => 'required|integer',
            '*.status' => 'required|boolean',
            '*.satuan' => 'required|array', // Ensure that 'satuan' is an array
            '*.satuan.*.nama_satuan' => 'required|string|max:255',
            '*.satuan.*.harga' => 'required|numeric',
            '*.satuan.*.status' => 'required|boolean',
        ]);

        $masterBarangs = [];
        
        DB::beginTransaction();

        foreach ($validatedData as $barangData) {
            // Simpan data ke tabel master_barang
            $masterBarang = Master_Barang::create([
                'nama_barang' => $barangData['nama_barang'],
                'img_url' => $barangData['img_url'],
                'qty' => $barangData['qty'],
                'status' => $barangData['status'],
            ]);

            // Simpan data satuan_barang terkait
            foreach ($barangData['satuan'] as $satuanData) {
                Satuan_Barang::create([
                    'id_barang' => $masterBarang->id,
                    'nama_satuan' => $satuanData['nama_satuan'],
                    'harga' => $satuanData['harga'],
                    'status' => $satuanData['status'],
                ]);
            }

            $masterBarangs[] = $masterBarang;
        }
        
        DB::commit();

        return response()->json([
            'message' => 'Data berhasil disimpan',
            'master_barang' => $masterBarangs
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error storing data: ' . $e->getMessage());
        return response()->json([
            'message' => 'Terjadi kesalahan saat menyimpan data',
            'error' => $e->getMessage()
        ], 500);
    }
}

        public function index()
    {
        // Buat ambil semua data Master_Barang beserta satuan terkait
        $masterBarangs = Master_Barang::with('satuan_barang')->get();  
        return response()->json($masterBarangs,200);
     }     // Mengembalikan response dengan semua data
        public function show($id)
    {
        $masterBarang = master_barang::with('satuan_Barang')->findOrFail($id); // Eager load related satuan_barang
        return response()->json([
            'master_barang' => $masterBarang,
            'satuan_barang' => $masterBarang->satuan_Barang,
        ], 200);
    }

    // Handle PUT request (update an existing master_barang and its related satuan_barang)
    public function update(Request $request, $id)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'nama_barang' => 'required|string',
            'img_url' => 'required|url',
            'qty' => 'required|integer',
            'status' => 'required|boolean',
            'satuan' => 'required|array',
            'satuan.*.nama_satuan' => 'required|string',
            'satuan.*.harga' => 'required|numeric',
            'satuan.*.status' => 'required|boolean',
        ]);

        // Find the master_barang record
        $masterBarang = master_barang::findOrFail($id);

        // Update the master_barang fields
        $masterBarang->update([
            'nama_barang' => $validatedData['nama_barang'],
            'img_url' => $validatedData['img_url'],
            'qty' => $validatedData['qty'],
            'status' => $validatedData['status'],
        ]);

            
        // Loop untuk menyimpan atau memperbarui data
        DB::transaction(function () use ($validatedData, $masterBarang) {
            $satuanIds = []; // Menyimpan ID satuan yang masih digunakan
    
            foreach ($validatedData['satuan'] as $index => $satuanData) {
                // Ambil satuan lama berdasarkan index atau buat baru
                $satuan = satuan_barang::where('id_barang', $masterBarang->id)
                    ->orderBy('id')
                    ->skip($index)
                    ->first();
    
                if ($satuan) {
                    // Update jika sudah ada
                    $satuan->update([
                        'nama_satuan' => $satuanData['nama_satuan'],
                        'harga' => $satuanData['harga'],
                        'status' => $satuanData['status'],
                    ]);
                } else {
                    // Insert jika belum cukup data lama
                    $satuan = satuan_barang::create([
                        'id_barang' => $masterBarang->id,
                        'nama_satuan' => $satuanData['nama_satuan'],
                        'harga' => $satuanData['harga'],
                        'status' => $satuanData['status'],
                    ]);
                }
    
                $satuanIds[] = $satuan->id;
            }
    
            // Hapus satuan yang tidak ada dalam request terbaru
            satuan_barang::where('id_barang', $masterBarang->id)
                ->whereNotIn('id', $satuanIds)
                ->delete();
        });
    
        // Ambil data terbaru setelah update
        $masterBarang = master_barang::with('satuan_barang')->findOrFail($masterBarang->id);
    
        return response()->json([
            'master_barang' => $masterBarang,
            'satuan_barang' => $masterBarang->satuan_barang,
        ], 200);
          }
        
    public function destroy($id)
    {
    try {
        DB::beginTransaction();

        $masterBarang = Master_Barang::findOrFail($id);
        
        // Hapus data satuan_barang terkait
        Satuan_Barang::where('id_barang', $masterBarang->id)->delete();
        
        // Hapus data master_barang
        $masterBarang->delete();
        
        DB::commit();

        return response()->json([
            'message' => 'Data berhasil dihapus'
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error deleting data: ' . $e->getMessage());
        return response()->json([
            'message' => 'Terjadi kesalahan saat menghapus data',
            'error' => $e->getMessage()
        ], 500);
    }
}
public function getSatuanByMasterBarang(Request $request)
{
    $nama_satuan = $request->input('nama_satuan'); // Ambil dari request

    if (!$nama_satuan) {
        return response()->json(['message' => 'Nama satuan harus diisi'], 400);
    }

    // Cari barang berdasarkan nama_satuan dari relasi satuan_barang
    $barang = Master_Barang::whereHas('satuan_barang', function ($query) use ($nama_satuan) {
        $query->where('nama_satuan', $nama_satuan);
    })->with(['satuan_barang' => function ($query) use ($nama_satuan) {
        $query->where('nama_satuan', $nama_satuan);
    }])->get();

    if ($barang->isEmpty()) {
        return response()->json(['message' => 'Barang dengan satuan tersebut tidak ditemukan'], 404);
    }

    return response()->json($barang);
    }
};

//updatenya salah
//updatenya dibikin kalau mau update data di hapus semua dulu baru di tambah yang diupdate
