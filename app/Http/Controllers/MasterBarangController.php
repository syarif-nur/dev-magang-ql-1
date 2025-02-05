<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\master_barang;



class MasterBarangController extends Controller
{
    public function index()
    {
        return response()->json(master_barang::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_barang' => 'required|string|max:50|unique:master_barang,kode_barang',
            'nama_barang' => 'required|string|max:100',
            'qty' => 'required|integer|min:0',
            'status' => 'required|string|max:50',
            'id_satuan' => 'required|exists:satuan_barang,id',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|integer|min:0',
        ]);

        $barang = master_barang::create($validatedData);
        return response()->json(['message' => 'Barang berhasil ditambahkan', 'data' => $barang], 201);
    }

    public function show($id)
    {
        return response()->json(master_barang::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $barang = master_barang::findOrFail($id);
        $barang->update($request->all());
        return response()->json(['message' => 'Barang berhasil diperbarui', 'data' => $barang]);
    }

    public function destroy($id)
    {
        master_barang::findOrFail($id)->delete();
        return response()->json(['message' => 'Barang berhasil dihapus']);
    }
}
