<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $fillable = [
        'nama_buku', 'pengarang', 'tahun_terbit', 'penerbit', 'jenis_buku', 'rak'
    ];

    protected $guarded = [];
}
