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
        'role_id' => 1,
        'casilla_id' => null,
    ],

    ['email'=>'casilla1A@gmail.com','username'=>'Casilla 1A','password'=>Hash::make('user2'),'role_id'=>3,'casilla_id'=>1],
    ['email'=>'casilla1B@gmail.com','username'=>'Casilla 1B','password'=>Hash::make('user3'),'role_id'=>3,'casilla_id'=>1],
    ['email'=>'casilla1C@gmail.com','username'=>'Casilla 1C','password'=>Hash::make('user4'),'role_id'=>3,'casilla_id'=>1],

    ['email'=>'casilla2A@gmail.com','username'=>'Casilla 2A','password'=>Hash::make('user5'),'role_id'=>3,'casilla_id'=>2],
    ['email'=>'casilla2B@gmail.com','username'=>'Casilla 2B','password'=>Hash::make('user6'),'role_id'=>3,'casilla_id'=>2],
    ['email'=>'casilla2C@gmail.com','username'=>'Casilla 2C','password'=>Hash::make('user7'),'role_id'=>3,'casilla_id'=>2],

    ['email'=>'casilla3A@gmail.com','username'=>'Casilla 3A','password'=>Hash::make('user8'),'role_id'=>3,'casilla_id'=>3],
    ['email'=>'casilla3B@gmail.com','username'=>'Casilla 3B','password'=>Hash::make('user9'),'role_id'=>3,'casilla_id'=>3],
    ['email'=>'casilla3C@gmail.com','username'=>'Casilla 3C','password'=>Hash::make('user10'),'role_id'=>3,'casilla_id'=>3],

    ['email'=>'casilla4A@gmail.com','username'=>'Casilla 4A','password'=>Hash::make('user11'),'role_id'=>3,'casilla_id'=>4],
    ['email'=>'casilla4B@gmail.com','username'=>'Casilla 4B','password'=>Hash::make('user12'),'role_id'=>3,'casilla_id'=>4],
    ['email'=>'casilla4C@gmail.com','username'=>'Casilla 4C','password'=>Hash::make('user13'),'role_id'=>3,'casilla_id'=>4],

    ['email'=>'casilla5A@gmail.com','username'=>'Casilla 5A','password'=>Hash::make('user14'),'role_id'=>3,'casilla_id'=>5],
    ['email'=>'casilla5B@gmail.com','username'=>'Casilla 5B','password'=>Hash::make('user15'),'role_id'=>3,'casilla_id'=>5],
    ['email'=>'casilla5C@gmail.com','username'=>'Casilla 5C','password'=>Hash::make('user16'),'role_id'=>3,'casilla_id'=>5],

    ['email'=>'casilla6A@gmail.com','username'=>'Casilla 6A','password'=>Hash::make('user17'),'role_id'=>3,'casilla_id'=>6],
    ['email'=>'casilla6B@gmail.com','username'=>'Casilla 6B','password'=>Hash::make('user18'),'role_id'=>3,'casilla_id'=>6],
    ['email'=>'casilla6C@gmail.com','username'=>'Casilla 6C','password'=>Hash::make('user19'),'role_id'=>3,'casilla_id'=>6],

    ['email'=>'casilla7A@gmail.com','username'=>'Casilla 7A','password'=>Hash::make('user20'),'role_id'=>3,'casilla_id'=>7],
    ['email'=>'casilla7B@gmail.com','username'=>'Casilla 7B','password'=>Hash::make('user21'),'role_id'=>3,'casilla_id'=>7],
    ['email'=>'casilla7C@gmail.com','username'=>'Casilla 7C','password'=>Hash::make('user22'),'role_id'=>3,'casilla_id'=>7],

    ['email'=>'casilla8A@gmail.com','username'=>'Casilla 8A','password'=>Hash::make('user23'),'role_id'=>3,'casilla_id'=>8],
    ['email'=>'casilla8B@gmail.com','username'=>'Casilla 8B','password'=>Hash::make('user24'),'role_id'=>3,'casilla_id'=>8],
    ['email'=>'casilla8C@gmail.com','username'=>'Casilla 8C','password'=>Hash::make('user25'),'role_id'=>3,'casilla_id'=>8],

    ['email'=>'casilla9A@gmail.com','username'=>'Casilla 9A','password'=>Hash::make('user26'),'role_id'=>3,'casilla_id'=>9],
    ['email'=>'casilla9B@gmail.com','username'=>'Casilla 9B','password'=>Hash::make('user27'),'role_id'=>3,'casilla_id'=>9],
    ['email'=>'casilla9C@gmail.com','username'=>'Casilla 9C','password'=>Hash::make('user28'),'role_id'=>3,'casilla_id'=>9],

    ['email'=>'casilla10A@gmail.com','username'=>'Casilla 10A','password'=>Hash::make('user29'),'role_id'=>3,'casilla_id'=>10],
    ['email'=>'casilla10B@gmail.com','username'=>'Casilla 10B','password'=>Hash::make('user30'),'role_id'=>3,'casilla_id'=>10],
    ['email'=>'casilla10C@gmail.com','username'=>'Casilla 10C','password'=>Hash::make('user31'),'role_id'=>3,'casilla_id'=>10],

    ['email'=>'casilla11A@gmail.com','username'=>'Casilla 11A','password'=>Hash::make('user32'),'role_id'=>3,'casilla_id'=>11],
    ['email'=>'casilla11B@gmail.com','username'=>'Casilla 11B','password'=>Hash::make('user33'),'role_id'=>3,'casilla_id'=>11],
    ['email'=>'casilla11C@gmail.com','username'=>'Casilla 11C','password'=>Hash::make('user34'),'role_id'=>3,'casilla_id'=>11],

    ['email'=>'casilla12A@gmail.com','username'=>'Casilla 12A','password'=>Hash::make('user35'),'role_id'=>3,'casilla_id'=>12],
    ['email'=>'casilla12B@gmail.com','username'=>'Casilla 12B','password'=>Hash::make('user36'),'role_id'=>3,'casilla_id'=>12],
    ['email'=>'casilla12C@gmail.com','username'=>'Casilla 12C','password'=>Hash::make('user37'),'role_id'=>3,'casilla_id'=>12],

    ['email'=>'casilla13A@gmail.com','username'=>'Casilla 13A','password'=>Hash::make('user38'),'role_id'=>3,'casilla_id'=>13],
    ['email'=>'casilla13B@gmail.com','username'=>'Casilla 13B','password'=>Hash::make('user39'),'role_id'=>3,'casilla_id'=>13],
    ['email'=>'casilla13C@gmail.com','username'=>'Casilla 13C','password'=>Hash::make('user40'),'role_id'=>3,'casilla_id'=>13],

    ['email'=>'casilla14A@gmail.com','username'=>'Casilla 14A','password'=>Hash::make('user41'),'role_id'=>3,'casilla_id'=>14],
    ['email'=>'casilla14B@gmail.com','username'=>'Casilla 14B','password'=>Hash::make('user42'),'role_id'=>3,'casilla_id'=>14],
    ['email'=>'casilla14C@gmail.com','username'=>'Casilla 14C','password'=>Hash::make('user43'),'role_id'=>3,'casilla_id'=>14],

    ['email'=>'casilla15A@gmail.com','username'=>'Casilla 15A','password'=>Hash::make('user44'),'role_id'=>3,'casilla_id'=>15],
    ['email'=>'casilla15B@gmail.com','username'=>'Casilla 15B','password'=>Hash::make('user45'),'role_id'=>3,'casilla_id'=>15],
    ['email'=>'casilla15C@gmail.com','username'=>'Casilla 15C','password'=>Hash::make('user46'),'role_id'=>3,'casilla_id'=>15],

    ['email'=>'casilla16A@gmail.com','username'=>'Casilla 16A','password'=>Hash::make('user47'),'role_id'=>3,'casilla_id'=>16],
    ['email'=>'casilla16B@gmail.com','username'=>'Casilla 16B','password'=>Hash::make('user48'),'role_id'=>3,'casilla_id'=>16],
    ['email'=>'casilla16C@gmail.com','username'=>'Casilla 16C','password'=>Hash::make('user49'),'role_id'=>3,'casilla_id'=>16],

    ['email'=>'casilla17A@gmail.com','username'=>'Casilla 17A','password'=>Hash::make('user50'),'role_id'=>3,'casilla_id'=>17],
    ['email'=>'casilla17B@gmail.com','username'=>'Casilla 17B','password'=>Hash::make('user51'),'role_id'=>3,'casilla_id'=>17],
    ['email'=>'casilla17C@gmail.com','username'=>'Casilla 17C','password'=>Hash::make('user52'),'role_id'=>3,'casilla_id'=>17],

    ['email'=>'casilla18A@gmail.com','username'=>'Casilla 18A','password'=>Hash::make('user53'),'role_id'=>3,'casilla_id'=>18],
    ['email'=>'casilla18B@gmail.com','username'=>'Casilla 18B','password'=>Hash::make('user54'),'role_id'=>3,'casilla_id'=>18],
    ['email'=>'casilla18C@gmail.com','username'=>'Casilla 18C','password'=>Hash::make('user55'),'role_id'=>3,'casilla_id'=>18],
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