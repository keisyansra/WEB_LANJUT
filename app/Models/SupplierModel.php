<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;

    protected $table = 'm_supplier'; // nama tabel di database
    protected $primaryKey = 'supplier_id'; // primary key di database
    protected $fillable = ['supplier_kode', 'supplier_nama', 'supplier_alamat']; 
}


