<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Vehiculo;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class VehiculosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Crear 10 vehículos de prueba
        foreach (range(1, 10) as $index) {
            Vehiculo::create([
                'placa' => $faker->unique()->bothify('???###'),  // Genera placas como 'AB-123'
                'TIV_Id' => rand(1, 4),  // Asumiendo que hay tipos de vehículos con IDs entre 1 y 5 (Tipo Vehiculo)
                'CLI_Id' => rand(1, 4),  // Cliente opcional (puede ser NULL), asumiendo que hay al menos 5 clientes
                'color' => $faker->safeColorName,  // Genera un color seguro aleatorio
                'marca' => $faker->company,  // Genera una marca de auto aleatoria
                'modelo' => $faker->word,  // Genera un modelo de vehículo aleatorio
            ]);
        }
    }
}
