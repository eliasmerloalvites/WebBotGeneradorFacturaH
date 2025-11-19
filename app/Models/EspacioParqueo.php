<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EspacioParqueo extends Model
{
    protected $table = 'espacios_parqueo';

    protected $fillable = [
        'COC_Id',
        'codigo',
        'TIV_Id',
        'state',
        'estado',
    ];

    // Relaciones
    public function cochera()
    {
        return $this->belongsTo(Cochera::class, 'COC_Id');
    }

    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'TIV_id');
    }
}
