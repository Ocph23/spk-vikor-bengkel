<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    public $fillable =[
        'id',
        'nama',
        'jenis_kelamin',
        'alamat',
    ];

    
    public function nilaiAlternatif()
    {
        return $this->hasMany(NilaiAlternatif::class);
    }

    public function periode()
    {
        return $this->hasmany(Periode::class);
    }
}
