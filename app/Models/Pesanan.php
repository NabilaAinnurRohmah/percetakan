<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $fillable = [
        'kode_pelanggan',
        'id_pelanggan',
        'nama_pesanan',
        'jenis_pesanan',
        'detail_pesanan',
        'status',
        'tanggal_pesanan',
        'tanggal_selesai',
        'total_harga',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
