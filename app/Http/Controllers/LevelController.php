<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LevelController extends Controller
{
    public function index() {
        //DB::insert('insert into m_level(level_kode, level_nama, created_at) values(?, ?, ?)', ['CUS', 'Pelanggan', now()]);
        //return "insert data baru berhasil";

        //$row = DB::update('update m_level set level_nama = ? where level_kode = ?', ['Customer', 'CUS']);
        //return "update data baru berhasil. Jumlah data yang diupdate: " .$row. ' baris';

        //$row = DB::delete('DELETE from m_level where level_kode = ?', ['CUS']);
        //return "delete data baru berhasil. Jumlah data yang dihapus: " .$row. ' baris';
        $data = DB::select('SELECT * from m_level');
        return view('level', ['data' => $data]);
    }
}
