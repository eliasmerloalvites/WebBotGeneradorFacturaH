<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey='id';
    public $timestamps=true;
    protected $fillable=[
        'nombre',
        'ruc',
        'direccion',
        'telefono',
        'email',
        'logo_url',
        'estado',
    ];
    
    protected $guarded =[

    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'EMP_Id', 'id');
    }
}
