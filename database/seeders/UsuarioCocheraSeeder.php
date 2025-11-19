<?php

namespace Database\Seeders;

use App\Models\Cochera;
use App\Models\User;
use App\Models\UsuarioCochera;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioCocheraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = User::all();
        $cocheras = Cochera::all();

        foreach ($usuarios as $usuario) {
            UsuarioCochera::create([
                'USU_Id' => $usuario->id,
                'COC_Id' => $cocheras->random()->id,
            ]);
        }
    }
}
