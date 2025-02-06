<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_barang extends Model
{
    use HasFactory;
    protected $table = 'master_barang';
    protected $fillable = ['id barang', 'nama_barang', 'img_url', 'qty', 'status', 'satuan'];

    public function satuan_Barang()
    {
        return $this->hasMany(satuan_barang ::class, 'id_barang'); 
    }
}
