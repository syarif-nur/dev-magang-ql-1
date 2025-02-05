<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\satuan_barang;
use Illuminate\Http\Request;

class SatuanBarangController extends Controller
{
    public function index()
    {
        return response()->json(satuan_barang::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_satuan' => 'required|string|max:50|unique:satuan_barang,nama_satuan',
        ]);

        $satuan = satuan_barang::create($validatedData);
        return response()->json(['message' => 'Satuan berhasil ditambahkan', 'data' => $satuan], 201);
    }

    public function show($id)
    {
        return response()->json(satuan_barang ::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $satuan = satuan_barang::findOrFail($id);
        $satuan->update($request->all());
        return response()->json(['message' => 'Satuan berhasil diperbarui', 'data' => $satuan]);
    }

    public function destroy($id)
    {
        satuan_barang::findOrFail($id)->delete();
        return response()->json(['message' => 'Satuan berhasil dihapus']);
    }
}

