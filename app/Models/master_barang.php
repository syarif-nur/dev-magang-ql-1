<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_barang extends Model
{

    use HasFactory;
    protected $table = 'master_barang';
    protected $primaryKey = 'id'; 
    protected $fillable = ['nama_barang', 'img_url', 'qty', 'status', ];

    public function satuan_barang()
    {
        return $this->hasMany(satuan_barang::class, 'id_barang', 'id');
    }
    
}
