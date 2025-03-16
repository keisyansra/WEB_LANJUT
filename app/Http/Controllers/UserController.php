<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $user = UserModel::where('level_id', 2)->count();
        //dd($user);
        return view('user', ['data' => $user]);
        // coba akses model UserModel 
        //$user = UserModel::where('username', 'manager9')->firstOrFail();
        //return view('user', ['data' => $user]);
    }
}
