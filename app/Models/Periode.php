<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    public $fillable =[
        'id',
        'nama',
        'keterangan',
        'status',
    ];
}
