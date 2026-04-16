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
            [
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'password' => Hash::make('desarrollo'),
                'role_id' => 1, //SuperAdmin
                'casilla_id' => null, //SuperAdmin
            ],
            [
                'email' => 'casilla1a@gmail.com',
                'username' => 'Casilla1A',
                'password' => Hash::make('123456'),
                'role_id' => 3, //Casilla
                'casilla_id' => 1, //Casilla 1 
            ],
            [
                'email' => 'casilla1b@gmail.com',
                'username' => 'Casilla1B',
                'password' => Hash::make('123456'),
                'role_id' => 3, //Casilla
                'casilla_id' => 1, //Casilla 1 
            ],
            [
                'email' => 'casilla1c@gmail.com',
                'username' => 'Casilla1C',
                'password' => Hash::make('123456'),
                'role_id' => 3, //Casilla
                'casilla_id' => 1, //Casilla 1 
            ],
            [
                'email' => 'casilla2a@gmail.com',
                'username' => 'Casilla2A',
                'password' => Hash::make('123456'),
                'role_id' => 3, //Casilla
                'casilla_id' => 1, //Casilla 1 
            ],
            [
                'email' => 'casilla2b@gmail.com',
                'username' => 'Casilla2B',
                'password' => Hash::make('123456'),
                'role_id' => 3, //Casilla
                'casilla_id' => 1, //Casilla 1 
            ],
            [
                'email' => 'casilla2c@gmail.com',
                'username' => 'Casilla2C',
                'password' => Hash::make('123456'),
                'role_id' => 3, //Casilla
                'casilla_id' => 1, //Casilla 1 
            ],
            [
                'email' => 'casilla3a@gmail.com',
                'username' => 'Casilla3A',
                'password' => Hash::make('123456'),
                'role_id' => 3, //Casilla
                'casilla_id' => 1, //Casilla 1 
            ],
            [
                'email' => 'casilla3b@gmail.com',
                'username' => 'Casilla3B',
                'password' => Hash::make('123456'),
                'role_id' => 3, //Casilla
                'casilla_id' => 1, //Casilla 1 
            ],
            [
                'email' => 'casilla3c@gmail.com',
                'username' => 'Casilla3C',
                'password' => Hash::make('123456'),
                'role_id' => 3, //Casilla
                'casilla_id' => 1, //Casilla 1 
            ]
        ];

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
