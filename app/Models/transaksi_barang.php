<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi_barang extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $fillable = ['id_barang', 'id_satuan', 'qty', 'total_harga', 'id_customer', 'status'];

    public function barang()
    {
        return $this->belongsTo(master_barang::class, 'id_barang');
    }

    public function satuan()
    {
        return $this->belongsTo(satuan_barang::class, 'id_satuan');
    }
}
