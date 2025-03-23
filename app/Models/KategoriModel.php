<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 'm_kategori'; // nama tabel di database
    protected $primaryKey = 'kategori_id'; // primary key di database
    protected $fillable = ['kategori_kode', 'kategori_nama']; 

    public function barang() : HashMany{
        return $this->hasMany(BarangModel::class, 'kategori_id', 'kategori_id');
    }
}


