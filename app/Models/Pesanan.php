<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $primaryKey = 'id_pesanan';

    protected $fillable = [
        'kode_pesanan',
        'id_pelanggan',
        'nama_pelanggan',
        'nama_pesanan',
        'jenis_pesanan',
        'detail_pesanan',
        'status',
        'tanggal_pesanan',
        'tanggal_selesai',
        'total_harga',
    ];

    public function detailLayanan()
    {
        return $this->hasMany(
            DetailPesanan::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

}
