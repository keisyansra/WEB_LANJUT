<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level'; // nama tabel di database
    protected $primaryKey = 'level_id'; // primary key di database
    protected $fillable = ['level_kode', 'level_nama']; 

    // relasi one to one ke UserModel
    public function user() : HashOne{
        return $this->hasOne(UserModel::class, 'level_id', 'level_id');
    } // relasi one-to-many, level memiliki banyak user
}


