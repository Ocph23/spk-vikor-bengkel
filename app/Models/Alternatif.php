<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    private $fillable =[
        'id',
        'nama',
        'jenis_kelamin',
        'alamat',
    ];
}
