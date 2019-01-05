<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Buku;
use Validator;
use Excel;
use File;
use Session;

class DataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->search != ''){

            if (strlen($request->search) <= 2){
                $Bukus = Buku::where('rak','like','%'.$request->search.'%')
                ->paginate(10);
                $Bukus->appends($request->only('search'));
                return view('data.index', compact('Bukus'));
            }

            $Bukus = Buku::where('nama_buku','like','%'.$request->search.'%')
            ->orWhere('pengarang', 'like', '%'.$request->search.'%')
            ->orWhere('tahun_terbit', 'like', '%'.$request->search.'%')
            ->orWhere('penerbit', 'like', '%'.$request->search.'%')
            ->orWhere('jenis_buku', 'like', '%'.$request->search.'%')
            ->paginate(10);
            $Bukus->appends($request->only('search'));
            return view('data.index', compact('Bukus'));

        }else{
            $Bukus = Buku::orderBy('id', 'desc')->paginate(10);
            $Bukus->appends($request->only('search'));
            return view('data.index', compact('Bukus'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => 'Form :attribute harus di isi!!!',
            'unique' => 'Data telah tersedia di database!'
        ];

        $validator = Validator::make($request->all(), [
            'nama_buku' => 'required|unique:bukus,nama_buku',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->messages()]);
        }

        $Bukus = Buku::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Bukus = Buku::find($id);
        return view ('data.index', compact('Bukus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $Bukus = Buku::findOrFail($id);
        $Bukus->nama_buku = $request->nama_buku;
        $Bukus->pengarang = $request->pengarang;
        $Bukus->tahun_terbit = $request->tahun_terbit;
        $Bukus->penerbit = $request->penerbit;
        $Bukus->rak = $request->rak;
        $Bukus->jenis_buku = $request->jenis_buku;
        $Bukus->save();
        return response()->json($Bukus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Buku::find($id)->delete();
    }
}
