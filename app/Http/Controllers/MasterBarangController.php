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

        // Loop through the 'satuan' array and update or create each related satuan_barang record
        foreach ($validatedData['satuan'] as $satuanData) {
            // Check if the satuan already exists or create a new one
            $satuan = satuan_barang::updateOrCreate(
                ['id_barang' => $masterBarang->id, 'nama_satuan' => $satuanData['nama_satuan']], // Check by id_barang and nama_satuan
                [
                    'harga' => $satuanData['harga'],
                    'status' => $satuanData['status'],
                ]
            );
        }

        $masterBarang = master_barang::with('satuan_Barang')->findOrFail($id);

        return response()->json([
            'master_barang' => $masterBarang,
            'satuan_barang' => $masterBarang->satuan_Barang, // Correct relationship method
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
 }

