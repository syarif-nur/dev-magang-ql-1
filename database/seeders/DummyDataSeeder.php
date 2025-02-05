<?php

use App\Models\master_barang;
use App\Models\satuan_barang;
use App\Models\transaksi_barang;
use Illuminate\Database\Seeder;
use App\Models\MasterBarang;
use App\Models\SatuanBarang;
use App\Models\Transaksi;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Dummy Data Master Barang
        $barangs = [
            ['nama_barang' => 'Sabun Mandi', 'img_url' => null, 'qty' => 100, 'status' => 1],
            ['nama_barang' => 'Detergen Cair', 'img_url' => null, 'qty' => 200, 'status' => 1],
        ];
        foreach ($barangs as $barang) {
            master_barang ::create($barang);
        }

        // Dummy Data Satuan Barang
        $satuans = [
            ['nama_satuan' => 'Botol', 'id_barang' => 1, 'harga' => 15000, 'status' => 1],
            ['nama_satuan' => 'Liter', 'id_barang' => 2, 'harga' => 20000, 'status' => 1],
        ];
        foreach ($satuans as $satuan) {
            satuan_barang ::create($satuan);
        }

        // Dummy Data Transaksi
        $transaksis = [
            ['id_barang' => 1, 'id_satuan' => 1, 'qty' => 3, 'total_harga' => 45000, 'id_customer' => 1, 'status' => 1],
            ['id_barang' => 2, 'id_satuan' => 2, 'qty' => 2, 'total_harga' => 40000, 'id_customer' => 2, 'status' => 1],
        ];
        foreach ($transaksis as $transaksi) {
            transaksi_barang::create($transaksi);
        }
    }
}
