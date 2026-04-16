<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasillaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $casillas = [
            // ÁREA RURAL
            [
                "type" => "Rural",
                "district" => 10,
                "perimeter" => "Lavín",
                "place" => "Vergel",
                "location" => "Plaza",
            ],
            [
                "type" => "Rural",
                "district" => 10,
                "perimeter" => "Lavín",
                "place" => "Brittingham",
                "location" => "Plaza",
            ],
            [
                "type" => "Rural",
                "district" => 10,
                "perimeter" => "Lavín",
                "place" => "6 de Octubre",
                "location" => "Plaza",
            ],
            [
                "type" => "Rural",
                "district" => 10,
                "perimeter" => "Sacramento",
                "place" => "Compás",
                "location" => "Plaza",
            ],
            [
                "type" => "Rural",
                "district" => 10,
                "perimeter" => "Sacramento",
                "place" => "Gregorio A. García",
                "location" => "Plaza",
            ],
            [
                "type" => "Rural",
                "district" => 10,
                "perimeter" => "Sacramento",
                "place" => "Arturo Mtz Adame",
                "location" => "Plaza",
            ],
            [
                "type" => "Rural",
                "district" => 10,
                "perimeter" => "Centro",
                "place" => "Cuba",
                "location" => "Plaza",
            ],
            [
                "type" => "Rural",
                "district" => 10,
                "perimeter" => "Centro",
                "place" => "Jabonoso",
                "location" => "Plaza",
            ],
            [
                "type" => "Rural",
                "district" => 10,
                "perimeter" => "Centro",
                "place" => "13 de Marzo",
                "location" => "Plaza",
            ],

            // ÁREA URBANA
            [
                "type" => "Urbana",
                "district" => 11,
                "perimeter" => "Centro",
                "place" => "Plaza de Armas",
                "location" => null,
            ],
            [
                "type" => "Urbana",
                "district" => 11,
                "perimeter" => "Fidel Velázquez",
                "place" => "Plaza Corazón",
                "location" => null,
            ],
            [
                "type" => "Urbana",
                "district" => 11,
                "perimeter" => "Miravalle",
                "place" => "Plaza",
                "location" => null,
            ],
            [
                "type" => "Urbana",
                "district" => 12,
                "perimeter" => "Flores Magón",
                "place" => "Torre Eiffel",
                "location" => null,
            ],
            [
                "type" => "Urbana",
                "district" => 12,
                "perimeter" => "Rinconada Napoles",
                "place" => "Filadelfia y Rcda. de las Azaleas",
                "location" => null,
            ],
            [
                "type" => "Urbana",
                "district" => 12,
                "perimeter" => "Felipe Ángeles",
                "place" => "Centro Comunitario",
                "location" => null,
            ],

            // ESPECIALES
            [
                "type" => "Especial",
                "district" => null,
                "perimeter" => "Hamburgo",
                "place" => "Paseo Gómez Palacio",
                "location" => null,
            ],
            [
                "type" => "Especial",
                "district" => null,
                "perimeter" => "Fracc. Sta Rosa",
                "place" => "Central de Abastos",
                "location" => null,
            ],
            [
                "type" => "Especial",
                "district" => null,
                "perimeter" => "Filadelfia",
                "place" => "UJED FICA",
                "location" => null,
            ],
        ];

        $data = array_map(function ($casilla) {
            return [
                'type' => $casilla['type'],
                'district' => $casilla['district'],
                'perimeter' => $casilla['perimeter'],
                'place' => $casilla['place'],
                'location' => $casilla['location'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $casillas);

        DB::table('casillas')->insert($data);
    }
}
