<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAlternatif extends Model
{

    use HasFactory;
    public $fillable =[
        'id',
        'periode_id',
        'alternatif_id',
        'kriteria_id',
        'sub_kriteria_id',
    ];

    public $timestamps = false;

    public function periode()
    {
        return $this->hasOne(Periode::class);
    }

    
    public function alternatif()
    {
        return $this->hasOne(Alternatif::class);
    }


    public function subkriteria()
    {
        return $this->hasOne(SubKriteria::class);
    }
}
