<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Mahasiswa_MataKuliah;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //fungsi eloquent menampilkan data menggunakan pagination
        if($request->has('search')){
            $mahasiswas = Mahasiswa::where('Nama', 'like', "%".$request->search."%")
                ->orWhere('Nim', $request->search)
                ->paginate(5);
        } else {
            // fungsi eloquent menampilkan data menggunakan pagination
            $mahasiswas = Mahasiswa::paginate(5);
        }
        return view('mahasiswas.index', compact('mahasiswas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $kelas = Kelas::all();
        return view('mahasiswas.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
            'Email' => 'required',
            'TanggalLahir' => 'required',
            ]);
            //fungsi eloquent untuk menambah data
            $mahasiswa = new Mahasiswa;
            $mahasiswa->nim = $request->get('Nim');
            $mahasiswa->nama = $request->get('Nama');
            $mahasiswa->kelas_id = $request->get('Kelas');
            $mahasiswa->jurusan = $request->get('Jurusan');
            $mahasiswa->no_handphone = $request->get('No_Handphone');
            $mahasiswa->email = $request->get('Email');
            $mahasiswa->tanggalLahir = $request->get('TanggalLahir');
            $mahasiswa->save();

            $kelas = new Kelas;
            $kelas->id = $request->get('Kelas');

            //fungsi eloquent untuk menambah data dengan relasi belongsTo

            $mahasiswa->kelas()->associate($kelas);
            $mahasiswa->save();


            //jika data berhasil ditambahkan, akan kembali ke halaman utama
            return redirect()->route('mahasiswas.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($Nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        $Mahasiswa = Mahasiswa::find($Nim);
        return view('mahasiswas.detail', compact('Mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($Nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        $Mahasiswa = Mahasiswa::with('kelas')->where('Nim' , $Nim)->first();
        $kelas = Kelas::all();
        return view('mahasiswas.edit', compact('Mahasiswa' , 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $Nim)
    {
        //melakukan validasi data
        $request->validate([
            'Nim' => 'required',
            'Nama' => 'required',
            'Kelas' => 'required',
            'Jurusan' => 'required',
            'No_Handphone' => 'required',
            'Email' => 'required',
            'TanggalLahir' => 'required',
            ]);

        $mahasiswa = Mahasiswa::with('kelas')->where('Nim' , $Nim)->first();
        $mahasiswa->nim = $request->get('Nim');
        $mahasiswa->nama = $request->get('Nama');
        $mahasiswa->kelas_id = $request->get('Kelas');
        $mahasiswa->jurusan = $request->get('Jurusan');
        $mahasiswa->no_handphone = $request->get('No_Handphone');
        $mahasiswa->email = $request->get('Email');
        $mahasiswa->tanggalLahir = $request->get('TanggalLahir');
        $mahasiswa->save();

        $kelas = new Kelas;
        $kelas->id = $request->get('Kelas');
        //fungsi eloquent untuk mengupdate data inputan kita
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        //jika data berhasil diupdate, akan kembali ke halaman utama
            return redirect()->route('mahasiswas.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($Nim)
    {
        //fungsi eloquent untuk menghapus data
            Mahasiswa::find($Nim)->delete();
            return redirect()->route('mahasiswas.index')
            -> with('success', 'Mahasiswa Berhasil Dihapus');
    }

    public function nilai($Nim){
        $mahasiswa = Mahasiswa_MataKuliah::with('mahasiswa')
            ->where('mahasiswa_id', $Nim)
            ->first();
        $nilai = Mahasiswa_MataKuliah::with('matakuliah')
            ->where('mahasiswa_id', $Nim)
            ->get();
        return view('mahasiswas.nilai', compact('mahasiswa', 'nilai'));
    }
}