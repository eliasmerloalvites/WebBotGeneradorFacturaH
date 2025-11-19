<?php

namespace Database\Seeders;

use App\Models\Tarifa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TarifaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tarifa::insert([            
            [
                'COC_Id' => 1,
                'TIV_Id' => 1,
                'precio_hora' => 3.00,
                'precio_dia' => 20.00,
                'precio_semana' => 100.00,
                'precio_mes' => 300.00,
                'moneda' => 'PEN',
                'estado' => 1,
            ],
            [
                'COC_Id' => 1,
                'TIV_Id' => 2,
                'precio_hora' => 4.00,
                'precio_dia' => 25.00,
                'precio_semana' => 120.00,
                'precio_mes' => 350.00,
                'moneda' => 'PEN',
                'estado' => 1,
            ],
            [
                'COC_Id' => 1,
                'TIV_Id' => 3,
                'precio_hora' => 5.00,
                'precio_dia' => 30.00,
                'precio_semana' => 150.00,
                'precio_mes' => 500.00,
                'moneda' => 'PEN',
                'estado' => 1,
            ],
            [
                'COC_Id' => 1,
                'TIV_Id' => 4,
                'precio_hora' => 6.00,
                'precio_dia' => 40.00,
                'precio_semana' => 200.00,
                'precio_mes' => 580.00,
                'moneda' => 'PEN',
                'estado' => 1,
            ],
        ]);
    }
}
