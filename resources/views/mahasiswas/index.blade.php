@extends('mahasiswas.layout')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left mt-2">
                <h2>JURUSAN TEKNOLOGI INFORMASI-POLITEKNIK NEGERI MALANG</h2>
            </div>
            <div class="float-right my-2">
                <a class="btn btn-success" href="{{ route('mahasiswas.create') }}"> Input Mahasiswa</a>
            </div>
        </div>
    </div>

    <div class="row justify-content-end">
        <div class="col-md-4">
            <form action="{{ route('mahasiswas.index') }}" accept-charset="UTF-8" method="get">
                <div class="input-group">
                    <input type="text" name="search" id="search" placeholder="Cari" class="form-control">
                    <span class="input-group-btn">
                        <input type="submit" value="Cari" class="btn btn-primary">
                    </span>
                    &emsp;
                    <a href="{{ route('mahasiswas.index') }}" class=" mt-1">
                        <span class="input-group-btn">
                            <button class="btn btn-danger" type="button" title="Refresh page">Refresh</button>
                                <span class="fas fa-sync-alt"></span>
                            </button>
                        </span>
                    </a>
                </div>
            </form>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table width="700px" class="table table-bordered" >
        <tr>
            <th>Nim</th>
            <th>Nama</th>
            <th>Image</th>
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>No_Handphone</th>
            <th>E-mail</th>
            <th width="100px">Tanggal Lahir</th>
            <th style="width:700%;">Action</th>
        </tr>
    @foreach ($mahasiswas as $Mahasiswa)
    <tr>
        <td>{{ $Mahasiswa->Nim }}</td>
        <td>{{ $Mahasiswa->Nama }}</td>
        <td> <img width="100px" src="{{asset('storage/'.$Mahasiswa->featured_image)}}"> </td>
        <td>{{ $Mahasiswa->Kelas->nama_kelas  }}</td>
        <td>{{ $Mahasiswa->Jurusan }}</td>
        <td>{{ $Mahasiswa->No_Handphone }}</td>
        <td>{{ $Mahasiswa->Email }}</td>
        <td>{{ $Mahasiswa->TanggalLahir }}</td>
        <td>
            <form action="{{ route('mahasiswas.destroy',$Mahasiswa->Nim) }}" method="POST">
                <a class="btn btn-info" href="{{ route('mahasiswas.show',$Mahasiswa->Nim) }}">Show</a>
                <br><br>
                <a class="btn btn-primary" href="{{ route('mahasiswas.edit',$Mahasiswa->Nim) }}">Edit</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
                <br><br>
                <a class="btn btn-warning" href="{{ route('mahasiswas.showNilai', $Mahasiswa->Nim) }}">Nilai</a>
            </form>
        </td>
    </tr>
    @endforeach
    </table>
    {{$mahasiswas->links('pagination::bootstrap-4')}}
@endsection