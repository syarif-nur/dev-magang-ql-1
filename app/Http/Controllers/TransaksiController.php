<?php

namespace App\Http\Controllers;

use App\Models\master_barang;
use App\Models\transaksi_barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        return response()->json(transaksi_barang ::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_barang' => 'required|exists:master_barang,id',
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|integer|min:0',
            'tanggal_transaksi' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $barang = master_barang::findOrFail($validatedData['id_barang']);
            if ($barang->stok < $validatedData['jumlah']) {
                return response()->json(['message' => 'Stok tidak mencukupi'], 400);
            }

            $barang->stok -= $validatedData['jumlah'];
            $barang->save();

            $transaksi = transaksi_barang::create($validatedData);

            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil ditambahkan', 'data' => $transaksi], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menyimpan transaksi', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        return response()->json(transaksi_barang::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $transaksi = transaksi_barang::findOrFail($id);
        $transaksi->update($request->all());
        return response()->json(['message' => 'Transaksi berhasil diperbarui', 'data' => $transaksi]);
    }

    public function destroy($id)
    {
        transaksi_barang::findOrFail($id)->delete();
        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }
}
