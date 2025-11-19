<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {        
        $faker = Faker::create();

        $users = [
            [
                'id' => 1,
                'name' => 'Abner Elias Merlo Alvites',
                'email' => 'merloalviteselias@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$10$QIRp4TJ1IyW4WHxUZnCd3eOmNqpldLkUUepaedJP/lMcNpIQTQCsa',
                'photo_extension' => null,
                'provider' => null,
                'provider_id' => null,
                'remember_token' => Str::random(60),
                'created_at' => Carbon::parse('2023-11-02 02:23:21'),
                'updated_at' => Carbon::parse('2024-04-03 22:02:46'),
                'estadousuario' => 1,
                'avatar' => '',
                'tipousuario' => 0,
                'numerodocumento' => null,
                'EMP_Id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Manuel Margas Azañero',
                'email' => 'manuel@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$12$IUcxZoouOi71BIa10F.iouUSviAcSfXn6s5x9dlMLzQvpJOka26mi',
                'photo_extension' => null,
                'provider' => null,
                'provider_id' => null,
                'remember_token' => Str::random(10),
                'created_at' => Carbon::parse('2024-04-01 23:07:44'),
                'updated_at' => Carbon::parse('2024-04-01 23:07:44'),
                'estadousuario' => 1,
                'avatar' => null,
                'tipousuario' => 0,
                'numerodocumento' => null,
                'EMP_Id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Rafael Lopez Aleaga',
                'email' => 'test@gmail.com',
                'email_verified_at' => null,
                'password' => '$2y$12$IUcxZoouOi71BIa10F.iouUSviAcSfXn6s5x9dlMLzQvpJOka26mi',
                'photo_extension' => null,
                'provider' => null,
                'provider_id' => null,
                'remember_token' => Str::random(10),
                'created_at' => Carbon::parse('2024-04-01 23:07:44'),
                'updated_at' => Carbon::parse('2024-04-01 23:07:44'),
                'estadousuario' => 1,
                'avatar' => null,
                'tipousuario' => 0,
                'numerodocumento' => null,
                'EMP_Id' => 1,
            ]
        ];
    
        // Agregar 40 usuarios aleatorios
        for ($i = 4; $i <= 42; $i++) {
            $users[] = [
                'id' => $i,
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => bcrypt('12345678'), // puedes usar bcrypt o un hash fijo
                'photo_extension' => null,
                'provider' => null,
                'provider_id' => null,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
                'estadousuario' => 1,
                'avatar' => null,
                'tipousuario' => 0,
                'numerodocumento' => $faker->unique()->numerify('########'),
                'EMP_Id' => 1,
            ];
        }
        
        // Insertar todo junto
        DB::table('users')->insert($users);
    }
}
