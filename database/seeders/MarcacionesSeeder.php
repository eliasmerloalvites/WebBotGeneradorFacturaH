<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\Marcacion;
use App\Models\Vehiculo;
use App\Models\EspacioParqueo;
use Carbon\Carbon;
use Faker\Factory as Faker;

class MarcacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Crear 10 marcaciones con fecha de hoy, sin salida ni monto
        foreach (range(1, 10) as $i) {
            $fechaEntrada = Carbon::today()
                ->setHour(rand(0, 23))
                ->setMinute(rand(0, 59))
                ->setSecond(rand(0, 59));

            Marcacion::create([
                'ESP_Id' => EspacioParqueo::inRandomOrder()->first()->id,
                'VEH_Id' => Vehiculo::inRandomOrder()->first()->id,
                'USU_Id' => $faker->numberBetween(1, 2),
                'fecha_entrada' => $fechaEntrada,
                'fecha_salida' => null,
                'monto_total' => null,
                'estado' => 'activo',
                'tipo_marcacion' => $faker->randomElement(['hora', 'dia', 'semana', 'mes'])
            ]);
        }

        // 👉 2. Crear 40 registros normales
        foreach (range(1, 40) as $index) {
            Marcacion::create([
                'ESP_Id'       => EspacioParqueo::inRandomOrder()->first()->id,
                'VEH_Id'       => Vehiculo::inRandomOrder()->first()->id,
                'USU_Id'       => $faker->numberBetween(1, 2),
                'fecha_entrada'=> $faker->dateTimeBetween('-1 month', 'now'),
                'fecha_salida' => $faker->dateTimeBetween('now', '+1 month'),
                'monto_total'  => $faker->randomFloat(2, 10, 500),
                'estado'       => $faker->randomElement(['activo', 'finalizado', 'cancelado']),
                'tipo_marcacion' => $faker->randomElement(['hora', 'dia', 'semana', 'mes']),
            ]);
        }
    }
}
