<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user'; 
    protected $primarykey = 'user_id';

    protected $fillable = ['username', 'nama', 'password', 'level_id'];

    //public function level(): BelongsTo {
       // return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    //}
}
