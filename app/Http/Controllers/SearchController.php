<?php

namespace App\Http\Controllers;
use App\Buku;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request){
        if($request->search != ''){
            $Bukus = Buku::where('nama_buku','like','%'.$request->search.'%')
            ->orWhere('pengarang', 'like', '%'.$request->search.'%')
            ->orWhere('tahun_terbit', 'like', '%'.$request->search.'%')
            ->orWhere('penerbit', 'like', '%'.$request->search.'%')
            ->orWhere('jenis_buku', 'like', '%'.$request->search.'%')
            ->paginate(10);
            $Bukus->appends($request->only('search'));
            if ($Bukus->isEmpty()){
                return redirect()->back()->with('warning', 'Maaf data tidak ditemukan');
            }else{
                return view('welcome', compact('Bukus'));
            }
        }else{
            return view('welcome');
        }
    }
}
