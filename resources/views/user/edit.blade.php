@extends('layouts.template')

@section('content')
<div class="container-fluid">
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">{{ $page->title }}</h3>
      <div class="card-tools">x</div>
    </div>
  
    <div class="card-body">
      @empty($user)
        <div class="alert alert-danger alert-dismissible">
          <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
          Data yang Anda cari tidak ditemukan.
          <a href="{{ url('/user') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
      @else
        <form method="POST" action="{{ url('/user/'.$user->user_id) }}" class="form-horizontal">
          @csrf
          {!! method_field('PUT') !!}
  
          <!-- Form Level -->
          <div class="form-group row">
            <label class="col-1 control-label col-form-label">Level</label>
            <div class="col-11">
              <select class="form-control" id="level_id" name="level_id" required>
                <option value="">- Pilih level -</option>
                @foreach($level as $item)
                  <option value="{{ $item->level_id }}" @if($item->level_id == $user->level_id) selected @endif>{{ $item->level_nama }}</option>
                @endforeach
              </select>
              @error('level_id')
                <small class="form-text text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>
  
          <!-- Form Username -->
          <div class="form-group row">
            <label class="col-1 control-label col-form-label">Username</label>
            <div class="col-11">
              <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
              @error('username')
                <small class="form-text text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>
  
          <!-- Form Nama -->
          <div class="form-group row">
            <label class="col-1 control-label col-form-label">Nama</label>
            <div class="col-11">
              <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
              @error('nama')
                <small class="form-text text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>
  
          <!-- Form Password -->
          <div class="form-group row">
            <label class="col-1 control-label col-form-label">Password</label>
            <div class="col-11">
              <input type="password" class="form-control" id="password" name="password">
              @error('password')
                <small class="form-text text-danger">{{ $message }}</small>
              @enderror
              <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti password user.</small>
            </div>
          </div>
  
          <!-- Tombol Simpan & Kembali -->
          <div class="form-group row">
            <div class="col-11 offset-1">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ url('/user') }}" class="btn btn-secondary">Kembali</a>
            </div>
          </div>
        </form>
      @endempty
    </div>
  </div>
</div>
@endsection