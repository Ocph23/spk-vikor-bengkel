<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    public $fillable =[
        'id',
        'kriteria_id',
        'nama',
        'bobot',
        'keterangan',
    ];

    public function kriteria(){
        return $this->belongsTo(Kriteria::class);
    }
}
