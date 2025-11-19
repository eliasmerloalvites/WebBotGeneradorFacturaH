<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PersonalTableSeeder::class,
            EmpresaSeeder::class,
            CocherasSeeder::class,
            UsersTableSeeder::class,
            UsuarioCocheraSeeder::class,
            RoleAndPermissionSeeder::class,
            TiposVehiculosSeeder::class,
            EspacioParqueoSeeder::class,
            TarifaSeeder::class,
            ClientesSeeder::class,
            VehiculosSeeder::class,
            MarcacionesSeeder::class
        ]);
    }
}
