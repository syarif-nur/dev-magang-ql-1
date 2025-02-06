<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->hasOne(MasterSatuan::class, 'id_barang', 'id');
    }

    public function transaksi_barang()
    {
        return $this->hasMany(TransaksiBarang::class);
    }

    protected function imgUrl() : Attribute
    {
        return Attribute::make(
            get: fn ($img_url) => url('/storage/barang/' . $img_url),
        );
    } 
}
