<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('empresas')->insert([[
            'nombre'     => 'Parkify S.A.C.',
            'ruc'        => '20481234567',
            'direccion'  => 'Av. Los Ingenieros 123, Lima',
            'telefono'   => '987654321',
            'email'      => 'contacto1@parkify.com',
            'logo_url'   => null,
            'estado'     => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nombre'     => 'PARKEO S.A.C.',
            'ruc'        => '20488894525',
            'direccion'  => 'Av. Oliva sa 25, Lima',
            'telefono'   => '987885847',
            'email'      => 'contacto2@parqueo.com',
            'logo_url'   => null,
            'estado'     => true,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'nombre'     => 'SERVICES PT S.A.C.',
            'ruc'        => '20488894528',
            'direccion'  => 'Jr. Alisos 54, Lima',
            'telefono'   => '952655874',
            'email'      => 'contacto3@servicept.com',
            'logo_url'   => null,
            'estado'     => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]]);
    }
}
