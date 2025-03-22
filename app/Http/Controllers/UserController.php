<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class UserController extends Controller {
    
    // Menampilkan halaman daftar user
    public function index() {
        $breadcrumb = (object)[
            'title' => 'Daftar Users',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; 
        $users = UserModel::with('level')->get();

        $level = LevelModel::all(); //ambil data level untuk filter level
        
        return view('user.index', compact('breadcrumb', 'page', 'activeMenu', 'users', 'level'));
    }

    // Mengambil data user untuk DataTables
    public function list(Request $request) {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');

        // filter data user berdasarkan level_id 
        if (!empty($request->level_id)) {
            $users->where('level_id', $request->level_id);
        }
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn = '<a href="' . url('/user/' . $user->user_id) . '" class="btn btn-info btn-sm">Detail</a>';
                $btn .= '<a href="' . url('/user/' . $user->user_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/user/' . $user->user_id) . '">'
                     . csrf_field()
                     . method_field('DELETE')
                     . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
                     </form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // menampilkan halaman form tambah user 
    public function create() {
        $breadcrumb = (object)[
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];
        $page = (object) [
            'title' => 'Tambah User Baru'
        ];
        $levels = LevelModel::all(); // ambil data level untuk ditampilkan
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'levels' => $levels, 
            'activeMenu' => $activeMenu
        ]);
    }

    // menyimpan data user baru
    public function store(Request $request) {
        $request->validate([
            //username harus diisi berupa string, minimal 3 karakter & bernilai unik di tabel m_user kolom username 
            'username'  => 'required|string|min:3|unique:m_user,username',
            'nama'      => 'required|string|max:100',
            'password'  => 'required|min:6', 
            'level_id'  => 'required|integer', //level_id harus diisi dan valid
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), // password dienkripsi sebelum disimpan
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil ditambahkan!');

    }

    //Menampilkan detail user 
    public function show(string $id) {
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail User'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif 

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit user
    public function edit(string $id) {
        $user = UserModel::find($id);
        $level = LevelModel::all();
    
        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];
    
        $page = (object) [
            'title' => 'Edit user'
        ];
    
        $activeMenu = 'user'; // set menu yang sedang aktif
    
        return view('user.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    
    // Menyimpan perubahan data user
    public function update(Request $request, string $id) {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|min:5', // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            'level_id' => 'required|integer' // level_id harus diisi dan berupa angka
        ]);
    
        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);
    
        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    //Menghapus data user 
    public function destroy(string $id) {

        $check = UserModel::find($id);
        if (!$check) {
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try { 
            UserModel::destroy($id); // hapus data level
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        }

        catch (\Illuminate\Database\QueryException $e) {
            // jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error 
            return redirect('/user')->with('error', 'Data user gagal dihapus, karena terdapat ketergantungan dengan data lain');
        }
    }


    // Menampilkan form tambah user
    public function tambah() {
        return view('user_tambah');
    }

    // Menyimpan data user baru
    public function tambah_simpan(Request $request) {
        // Validasi data
        $request->validate([
            'username' => 'required|string|max:50',
            'nama' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'level_id' => 'required|integer|exists:levels,level_id',
        ]);

        // Simpan data
        UserModel::create([
            'username' => trim($request->username),
            'nama' => trim($request->nama),
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'User berhasil ditambahkan!');
    }

    // Menampilkan form edit user
    public function ubah($id) {
        $user = UserModel::find($id);
        
        if (!$user) {
            return back()->withErrors('User tidak ditemukan.');
        }

        return view('user_ubah', ['data' => $user]);
    }

    // Menyimpan data setelah diubah
    public function ubah_simpan($id, Request $request) {
        $user = UserModel::find($id);

        if (!$user) {
            return back()->withErrors('User tidak ditemukan.');
        }

        // Validasi data
        $request->validate([
            'username' => 'required|string|max:50',
            'nama' => 'required|string|max:100',
            'password' => 'nullable|string|min:6',
            'level_id' => 'required|integer|exists:levels,level_id',
        ]);

        // Update data
        $user->username = trim($request->username);
        $user->nama = trim($request->nama);
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        
        $user->level_id = $request->level_id;
        $user->save();

        return redirect('/user')->with('success', 'Data user berhasil diubah!');
    }

    // Menghapus data user
    public function hapus($id) {
        $user = UserModel::find($id);

        if (!$user) {
            return back()->withErrors('User tidak ditemukan.');
        }

        $user->delete();
        return redirect('/user')->with('success', 'User berhasil dihapus!');
    }
}
