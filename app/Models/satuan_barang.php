<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class satuan_barang extends Model
{
    use HasFactory;
    protected $table = 'satuan_barang';
    protected $fillable = ['nama_satuan', 'id_barang', 'harga', 'status'];

    public function masterBarang()
    {
        return $this->belongsTo(master_barang ::class, 'id_barang');
    }
}
