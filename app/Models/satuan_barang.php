<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class satuan_barang extends Model
{
    use HasFactory;
    protected $table = 'satuan_barang';
    protected $primaryKey = 'id';
    protected $fillable = ['id_barang','nama_satuan', 'harga', 'status'];
    public function master_Barang()
    {
        return $this->belongsTo(master_barang ::class, 'id_barang', 'id');
    }
}
