@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <a href="{{ url('/user/create') }}" class="btn btn-sm btn-primary float-right">Tambah</a>
    </div>

    <div class="card-body">
        @if(isset($users) && $users->isEmpty())
            <div class="alert alert-danger">
                Data user tidak ditemukan.
            </div>
        @else
        <div class="row">
            <div class="col-md-12">
                <div class="form-group-row">
                    <label class="col-md-2 control-label col-form-label">Filter : </label>
                    <div class="col-3">
                        <select class="form-control" id="level_id" name="level_id" required>
                            <option value="">Semua Level</option>
                            @foreach($level as $item)
                                <option value="{{ $item->level_id }}">{{ $item->level_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Level Pengguna</small>
                    </div>
                </div>
            </div>
        </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->level->level_nama }}</td>
                        <td>
                            <a href="{{ url('/user/'.$user->user_id) }}" class="btn btn-sm btn-info">Detail</a>
                            <a href="{{ url('/user/'.$user->user_id.'/edit') }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ url('/user/'.$user->user_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
 

@push('css')
@endpush

@push('js')
<script>
    $(document).ready(function() {
        var table = $('#userTable').DataTable({
            //serverSide: true, jika ingin menggunakan server side processing 
            processing: true,
            serverSide : true,
            ajax: {
                "url": "{{ url('user/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.level_id = $('#level_id').val();
                    return d;
                }
            },
            columns: [
                {
                    // nomor urut dari laravel datatable addIndexColumns()
                    data : "DT_RowIndex",
                    className : "text-center",
                    orderable : false,
                    searchable : false,
                }, 
                {
                    data : "username",
                    className : "",
                    // orderable : true, jika ingin kolom ini bisa diurutkan
                    orderable : true,
                    // searchable true jika ingin kolom ini bisa dicari
                    searchable : true,
                },
                {
                    data : "nama",
                    className : "",
                    orderable : true,
                    searchable : true,
                },
                {
                    // mengambil data level hasil dari ORM berelasi 
                    data : "level.level_nama",
                    className : "",
                    orderable : false,
                    searchable : false,
                },
                {
                    data : "aksi",
                    className : "",
                    orderable : false,
                    searchable : false,
                }
            ]
        });

        $('#level_id').on('change', function()) {
            dataUser.ajax.reload();
        }
    });
</script>
@endpush 