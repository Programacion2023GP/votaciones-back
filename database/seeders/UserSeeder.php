<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin
            [
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'password' => Hash::make('desarrollo'),
                'role_id' => 1, // SuperAdmin
                'casilla_id' => null,
            ],
        ];

        // Generar 3 usuarios por cada casilla del 1 al 18
        for ($casilla = 1; $casilla <= 18; $casilla++) {
            foreach (['A', 'B', 'C'] as $letra) {
                $users[] = [
                    'email' => "casilla{$casilla}{$letra}@gmail.com",
                    'username' => "Casilla {$casilla}{$letra}",
                    'password' => Hash::make("user{$casilla}"),
                    'role_id' => 3, // Rol de casilla
                    'casilla_id' => $casilla,
                ];
            }
        }

        $data = array_map(function ($user) {
            return [
                'email' => $user['email'],
                'username' => $user['username'],
                'password' => $user['password'],
                'role_id' => $user['role_id'],
                'casilla_id' => $user['casilla_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $users);

        DB::table('users')->insert($data);
    }
}