<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jml = 2;
        if (strlen($katakunci)) {
            $data = Mahasiswa::where('nim', 'like', "%$katakunci%")->orWhere('nama', 'like', "%$katakunci%")->orWhere('jurusan', 'like', "%$katakunci%")->paginate($jml);
        } else {
            $data = Mahasiswa::orderBy('nim', 'DESC')->paginate(2);
        }

        return view('mahasiswa.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Session::flash('nim', $request->nim);
        Session::flash('nama', $request->nama);
        Session::flash('jurusan', $request->jurusan);

        $request->validate(
            [
                'nim' => 'required|numeric|unique:mahasiswa,nim',
                'nama' => 'required',
                'jurusan' => 'required'
            ],
            [
                'nim.required' => 'Kolom NIM Wajib diisi',
                'nim.numeric' => 'Kolom NIM Wajib diisi dengan Angka',
                'nim.unique' => 'NIM Yang dimasukan sudah ada',
                'nama.required' => 'Kolom NAMA Wajib diisi',
                'jurusan.required' => 'Kolom JURUSAN Wajib diisi'
            ]
        );

        $data = [
            'nim' => $request->nim,
            'nama' => $request->nama,
            'jurusan' => $request->jurusan
        ];
        Mahasiswa::create($data);
        return redirect()->to('mahasiswa')->with('success', 'Berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Mahasiswa::where('nim', $id)->first();
        return view('mahasiswa.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'nama' => 'required',
                'jurusan' => 'required'
            ],
            [
                'nama.required' => 'Kolom NAMA Wajib diisi',
                'jurusan.required' => 'Kolom JURUSAN Wajib diisi'
            ]
        );

        $data = [
            'nama' => $request->nama,
            'jurusan' => $request->jurusan
        ];
        Mahasiswa::where('nim', $id)->update($data);
        return redirect()->to('mahasiswa')->with('success', 'Berhasil Mengubah Data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Mahasiswa::where('nim', $id)->delete();
        return redirect()->to('mahasiswa')->with('success', 'Berhasil Mengkosost Data');
    }
}