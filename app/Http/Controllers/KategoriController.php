<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Cast\Object_;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;



class KategoriController extends Controller {
    //menampilkan halaman awal user
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar kategori yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategori'; 
        $kategori = kategoriModel::all(); 
        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request) {
        $kategori = kategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        // Filter data user berdasarkan kategori
        if ($request->kategori_id) {
            $kategori->where('kategori_id',$request->kategori_id);
        }

        return DataTables::of($kategori)
        ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom:DT_RowIndex)
        ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
            $btn = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id .
            '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';

            $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id .
            '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';

            $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->kategori_id .
            '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

            return $btn; 
        })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        ->make(true);
    }
    
    // Menampilkan halaman form tambah user
    public function create() {
        $breadcrumb = (object) [
            'title' => 'Tambah kategori',
            'list' => ['Home', 'kategori', 'Tambah'] 
        ];

        $page = (object) [
            'title' => 'Tambah kategori baru'
        ];

        $kategori = kategoriModel::all(); // ambil data kategori untuk ditampilkan di form
        $activeMenu = 'kategori'; // set menu yang sedang aktif
        return view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data user baru
    public function store(Request $request) {
        $request->validate([
            'kategori_kode' => 'required | string | min:2 | unique:m_kategori, kategori_kode',
            'kategori_nama' => 'required | string | max:100'
        ]);

        // simpan ke database 
        kategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama
        ]);

        return redirect('/kategori')->with('success', 'Data user berhasil disimpan');
    }

    // Menampilkan detail user
    public function show(string $id) {
        $kategori = kategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail kategori',
            'list' => ['Home', 'kategori', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail kategori'
        ];

        $activeMenu = 'kategori'; // set menu yang sedang aktif

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit user
    public function edit(string $id) {
        $kategori = kategoriModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit kategori',
            'list' => ['Home', 'kategori', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit kategori'
        ];

        $activeMenu = 'kategori'; // set menu yang sedang aktif
        return view('kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $id) {
        $request->validate([
            'kategori_kode' => 'required|string|max:50|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
            'kategori_nama' => 'required|string|max:100'
        ]);

        kategoriModel::find($id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);

        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }

    // Menghapus data user
    public function destroy(string $id) {
        $check = kategoriModel::find($id);
        if (!$check) { // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }

        try {
            kategoriModel::destroy($id); // Hapus data user

            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {

            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax() {
        $kategori = kategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('kategori.create_ajax') ->with('kategori', $kategori);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|max:50|unique:m_kategori,kategori_kode,',
                'kategori_nama' => 'required|string|max:100'
            ];
        
            $validator = Validator::make($request->all(), $rules);
        
            if($validator->fails()){
                return response()->json([
                    'status'  => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField'=> $validator->errors(), // pesan error validasi
                ]);
            }
        
            kategoriModel::create($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Data kategori berhasil disimpan'
            ]);
        }
        return redirect('/');
    }  
        
    //menampilkan halaman form edit user ajax
    public function edit_ajax(string $id) {
        $kategori = kategoriModel::find($id);
        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id) {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|min:2|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
                'kategori_nama' => 'required|string|min:3|max:50'
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                    return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }

            $check = kategoriModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } 
            else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id) {
        $kategori = kategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax(Request $request, $id) {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = kategoriModel::find($id);
            if ($kategori) {
                $kategori->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
}