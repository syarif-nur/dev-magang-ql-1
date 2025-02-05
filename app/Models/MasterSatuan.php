<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSatuan extends Model
{
    use HasFactory;
    protected $table = 'master_satuan'; 
    protected $primaryKey = 'id'; 
    public $fillable = [
        'nama_satuan',
        'id_barang',
        'harga',
        'status',
    ];

    public function master_barang()
    {
        return $this->belongsTo(MasterBarang::class);
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiBarang::class);
    }
}
