<?php

namespace App\Http\Controllers\Api;

use App\Models\MasterBarang;
use App\Models\MasterSatuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\MasterBarangResource;

class MasterBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = MasterBarang::with('master_satuan')->get();
        return response()->json([
            'message' => 'List semua barang',
            'data' => $barang
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '*.nama_barang' => 'required|string|max:255',
            '*.img_url' => 'required|url',
            '*.qty' => 'required|integer',
            '*.status' => 'required|integer',
            '*.satuan' => 'required|array',
            '*.satuan.*.nama_satuan' => 'required|string|max:255',
            '*.satuan.*.harga' => 'required|numeric',
            '*.satuan.*.status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $barangList = [];

            foreach ($request->all() as $barangData) {
                $barang = MasterBarang::create([
                    'nama_barang' => $barangData['nama_barang'],
                    'img_url' => $barangData['img_url'],
                    'qty' => $barangData['qty'],
                    'status' => $barangData['status'],
                ]);
    
                foreach ($barangData['satuan'] as $satuan) {
                    MasterSatuan::create([
                        'nama_satuan' => $satuan['nama_satuan'],
                        'id_barang' => $barang->id,
                        'harga' => $satuan['harga'],
                        'status' => $satuan['status'],
                    ]);
                }
    
                // Menyimpan data master_barang dan satuan
                $barangWithSatuan = $barang->load('master_satuan');
                $barangList[] = $barangWithSatuan;
            }
    
            return response()->json([
                'message' => 'Data master barang dan satuan berhasil disimpan',
                'data' => $barangList
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $barang = MasterBarang::with('master_satuan')->find($id);

        if (!$barang) {
            return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail barang',
            'data' => $barang
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|string|max:255',
            'img_url' => 'required|url',
            'qty' => 'required|integer',
            'status' => 'required|integer',
            'satuan' => 'required|array',
            'satuan.*.nama_satuan' => 'required|string|max:255',
            'satuan.*.harga' => 'required|numeric',
            'satuan.*.status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $barang = MasterBarang::find($id);
            if (!$barang) {
                return response()->json(['message' => 'Barang tidak ditemukan'], 404);
            } 

            $barang->update([
                'nama_barang' => $request->nama_barang,
                'img_url' => $request->img_url,
                'qty' => $request->qty,
                'status' => $request->status,
            ]); 

            MasterSatuan::where('id_barang', $id)->delete();

            foreach ($request->satuan as $satuan) {
                MasterSatuan::create([
                    'nama_satuan' => $satuan['nama_satuan'],
                    'id_barang' => $barang->id,
                    'harga' => $satuan['harga'],
                    'status' => $satuan['status'],
                ]);
            }
    
            $barangWithSatuan = $barang->load('master_satuan');

            return response()->json([
                'message' => 'Data master barang dan satuan berhasil diperbarui',
                'data' => $barangWithSatuan,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang = MasterBarang::find($id);
        if (!$barang) {
        return response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        try {
            $barang->delete();
            
            MasterSatuan::where('id_barang', $id)->delete();

            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // mencari barang berdasarkan satuan "Pack"
    public function getBarangBySatuanPack()
    {
        $barang = MasterBarang::with('master_satuan')
            ->whereHas('master_satuan', function ($query) {
                $query->where('nama_satuan', 'like', '%Pack%');
        })
        ->get();

        if ($barang->isEmpty()) {
            return response()->json(['message' => 'Barang yang memiliki nama satuan "Pack" tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Barang yang memiliki nama satuan "Pack"',
            'data' => $barang
        ], 200);
    }
}
