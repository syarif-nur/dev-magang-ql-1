<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterBarang extends Model
{
    use HasFactory;
    protected $table = 'master_barang'; 
    protected $primaryKey = 'id'; 
    public $fillable = [
        'nama_barang',
        'img_url',
        'qty',
        'status',
    ];

    public function master_satuan()
    {
        return $this->hasOne(MasterSatuan::class);
    }

    public function transaksi_barang()
    {
        return $this->hasMany(TransaksiBarang::class);
    }
}
