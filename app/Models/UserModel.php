<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;
  
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

   protected $table = 'm_user'; // mendefinisikan nama tabel yang digunakan oleh model ini
   protected $primaryKey = 'user_id'; // Mendefinisikan primary key dari tabel yang digunakan

   protected $fillable = [
      'level_id',
      'username',
      'password',
      'nama',
      'image'
    ];
   
   protected $hidden = ['password']; // jangan ditampilkan saat select 
   protected $casts = ['password' => 'hashed']; // casting password agar otomatis di hash 

    // relasi ke tabel level 
   public function level(): BelongsTo{
    return $this->belongsTo(LevelModel:: class, 'level_id', 'level_id');
   }

   protected function image(): Attribute {
    return Attribute::make(
      get: fn ($image) => url('/storage/posts/' .$image),
    );
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