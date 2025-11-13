<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'titulo',
        'autor',
        'categoria',
        'ejemplares',
        'area_estante',
    ];

    protected $table = 'libros';
}

