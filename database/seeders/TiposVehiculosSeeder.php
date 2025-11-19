<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TiposVehiculosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('tipos_vehiculos')->insert([
            [
                'nombre'      => 'Moto',
                'tamaño'      => 'Pequeño',
                'descripcion' => 'Vehículo de dos ruedas, ideal para desplazamientos urbanos.',
                'imagen'      => '1.png',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'nombre'      => 'Auto',
                'tamaño'      => 'Mediano',
                'descripcion' => 'Vehículo de cuatro ruedas para pasajeros.',
                'imagen'      => '2.png',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'nombre'      => 'Camioneta',
                'tamaño'      => 'Grande',
                'descripcion' => 'Vehículo de carga ligera o transporte de pasajeros.',
                'imagen'      => '3.png',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
            [
                'nombre'      => 'Camión',
                'tamaño'      => 'Extra grande',
                'descripcion' => 'Vehículo de carga pesada.',
                'imagen'      => '4.png',
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);
    }
}
