<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Model
{
    use HasFactory;

   protected $table = 'm_user'; // mendefinisikan nama tabel yang digunakan oleh model ini
   protected $primaryKey = 'user_id'; // Mendefinisikan primary key dari tabel yang digunakan
   protected $fillable = ['level_id','username','password','nama'];
   
   protected $hidden = ['password']; // jangan ditampilkan saat select 
   protected $casts = ['password' => 'hashed']; // casting password agar otomatis di hash 

    // relasi ke tabel level 
   public function level(): BelongsTo{
    return $this->belongsTo(LevelModel:: class, 'level_id', 'level_id');
   }
   
   // mendapatkan nama role 
   public function getRoleName() : string {
     return $this->level->level_nama; 
   }

   // cek apakah user memiliki role tertentu 
   public function hasRole($role) : bool {
     return $this->level->level_kode === $role;
   }
}