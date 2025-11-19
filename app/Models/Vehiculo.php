<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    // Se especifica el nombre de la tabla
    protected $table = 'vehiculos';

    // Campos asignables
    protected $fillable = [
        'placa',
        'TIV_Id',
        'CLI_Id',
        'color',
        'marca',
        'modelo'
    ];

    /**
     * Relación con el modelo TipoVehiculo.
     * Se asume que existe el modelo TipoVehiculo en App\Models.
     */
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'TIV_Id');
    }

    /**
     * Relación opcional con el modelo Cliente.
     * Se asume que existe el modelo Cliente en App\Models.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'CLI_Id');
    }
}
