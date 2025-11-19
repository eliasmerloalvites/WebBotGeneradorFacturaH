<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CocherasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        DB::table('cocheras')->insert([
            [
                'EMP_Id' => 1, // Suponiendo que hay una empresa con ID 1
                'nombre' => 'Cochera Central',
                'direccion' => 'Av. Principal 123',
                'latitud' => -34.603684,
                'longitud' => -58.381559,
                'estado' => 'activa',
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'EMP_Id' => 1, // Suponiendo que hay una empresa con ID 1
                'nombre' => 'Cochera Norte',
                'direccion' => 'Av. Norte 456',
                'latitud' => -34.603684,
                'longitud' => -58.381559,
                'estado' => 'activa',
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            [
                'EMP_Id' => 1, // Suponiendo que hay una empresa con ID 2
                'nombre' => 'Cochera Sur',
                'direccion' => 'Av. Sur 789',
                'latitud' => -34.603684,
                'longitud' => -58.381559,
                'estado' => 'inactiva',
                'created_at'  => $now,
                'updated_at'  => $now
            ],
            
            [
                'EMP_Id' => 2, // Suponiendo que hay una empresa con ID 2
                'nombre' => 'Cochera PARKEO',
                'direccion' => 'Av. Oliva sa 25, Lima',
                'latitud' => -34.603684,
                'longitud' => -58.381559,
                'estado' => 'activa',
                'created_at'  => $now,
                'updated_at'  => $now
            ],            
            [
                'EMP_Id' => 3, // Suponiendo que hay una empresa con ID 3
                'nombre' => 'Cochera SERVICES PT',
                'direccion' => 'Av. Oliva sa 25, Lima',
                'latitud' => -34.603684,
                'longitud' => -58.381559,
                'estado' => 'activa',
                'created_at'  => $now,
                'updated_at'  => $now
            ],
        ]);
    }
}
