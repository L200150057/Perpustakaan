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
        $validator = Validator::make($request->all(), [
            'nama_buku' => 'required|unique:bukus,nama_buku',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>'Data telah tersedia di database!']);
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

    public function create_excel(Request $request)
    {
        // Get current data from items table
        $nama_bukus = Buku::pluck('nama_buku')->toArray();

        if($request->hasFile('excel')){
            $extension = File::extension($request->excel->getClientOriginalName());
            if ($extension == "xlsx" || $extension == "xls" || $extension == "csv") {
                $path = $request->excel->getRealPath();
                $data = Excel::load($path, function($reader) {
                })->get();
                if(!empty($data) && $data->count()){

                    $insert = array();

                    foreach ($data as $key => $value) {

                        // Skip title previously added using in_array
                        if (in_array($value->nama_buku, $nama_bukus))
                            continue;

                        $insert[] = [
                        'nama_buku' => $value->nama_buku,
                        'pengarang' => $value->pengarang,
                        'tahun_terbit' => $value->tahun_terbit,
                        'penerbit' => $value->penerbit,
                        'rak' => $value->rak,
                        'jenis_buku' => $value->jenis_buku,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                        ];

                        // Add new title to array
                        $nama_bukus[] = $value->nama_buku;
                    }

                    if(!empty($insert)){

                        $validator = Validator::make($insert, [
                            '*.nama_buku' => 'required|unique:bukus,nama_buku|distinct|min:3',
                        ]);

                        if ($validator->fails()) {
                            Session::flash('error', 'Sebagian data Data telah tersedia di database! Mohon di cek lagi');
                            return back();
                        }else{
                            $insertData = Buku::insert($insert);
                            if ($insertData) {
                                Session::flash('success', 'Your Data has successfully imported');
                            }else {
                                Session::flash('error', 'Error inserting the data..');
                                return back();
                            }
                        }
                    }
                }

                return back();

            }else {
                Session::flash('error', 'File is a '.$extension.' file.!! Please upload a valid xls/csv file..!!');
                return back();
            }
        }
    }
}
