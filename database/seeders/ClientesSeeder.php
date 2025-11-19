<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Cliente;
use Faker\Factory as Faker;

class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Crear 10 clientes de prueba
        foreach (range(1, 10) as $index) {
            Cliente::create([
                'nombre' => $faker->name,  // Genera un nombre aleatorio
                'documento' => $faker->unique()->numerify('########'),  // Genera un número aleatorio de 10 dígitos (puede simular un DNI o RUC)
                'telefono' => $faker->phoneNumber,  // Genera un número de teléfono aleatorio
                'email' => $faker->unique()->safeEmail,  // Genera un correo electrónico único
            ]);
        }
    }
}
