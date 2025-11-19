<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cochera extends Model
{
    use HasFactory;

    /**
     * La tabla asociada al modelo.
     */
    protected $table = 'cocheras';

    /**
     * Los atributos que son asignables masivamente.
     */
    protected $fillable = [
        'EMP_Id',
        'nombre',
        'direccion',
        'latitud',
        'longitud',
        'estado'
    ];
}
