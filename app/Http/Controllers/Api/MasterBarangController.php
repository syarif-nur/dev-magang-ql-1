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

        DB::beginTransaction();

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
    
                // Load data satuan untuk response
                $barangList[] = $barang->load('master_satuan');
            }
    
            DB::commit();
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
            'img_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'qty' => 'required|integer',
            'status' => 'required|integer',
            'satuan' => 'required|array',
            'master_satuan.nama_satuan' => 'required|string|max:255',
            'master_satuan.harga' => 'required|numeric',
            'master_satuan.status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB:: beginTransaction();
        try {
            $barang = MasterBarang::find($id);
            if (!$barang) {
                return response()->json(['message' => 'Barang tidak ditemukan'], 404);
            } 

            $imgUrl = $barang->img_url;
            if ($request->hasFile('img_url')) {
                $imgUrl = $request->file('img_url')->store('barang', 'public');
            }

            $barang->update([
                'nama_barang' => $request->nama_barang,
                'img_url' => $imgUrl,
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
    
            DB::commit();
            return response()->json([
                'message' => 'Data berhasil diperbarui',
                'data' => $barang->load('satuan')
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

    DB::beginTransaction();
    try {
        $barang->delete();
        DB::commit();
        return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
