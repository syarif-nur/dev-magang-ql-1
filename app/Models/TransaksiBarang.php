<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiBarang extends Model
{
    use HasFactory;
    protected $table = 'transaksi_barang'; 
    protected $primaryKey = 'id'; 
    public $fillable = [
        'id_barang',
        'id_satuan',
        'qty',
        'total_harga',
        'id_customer',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }  

    public function master_barang()
    {
        return $this->belongsTo(MasterBarang::class);
    }

    public function master_satuan()
    {
        return $this->belongsTo(MasterSatuan::class);
    }
}
