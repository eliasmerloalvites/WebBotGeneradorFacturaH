<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = 'tarifas';

    protected $fillable = [
        'COC_Id',
        'TIV_Id',
        'precio_hora',
        'precio_dia',
        'precio_mes',
        'moneda',
        'estado',
    ];

    public function cochera()
    {
        return $this->belongsTo(Cochera::class, 'COC_Id');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'TIV_Id');
    }
    
}
