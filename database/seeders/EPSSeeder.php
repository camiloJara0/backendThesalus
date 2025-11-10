<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EPSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $eps = [
            [
                "id" => 1,
                "nombre" => "COOSALUD EPS-S",
                "codigo" => "ESS024 - EPS042",
                "nit" => "900226715",
            ],
            [
                "id" => 2,
                "nombre" => "NUEVA EPS",
                "codigo" => "EPS037 - EPSS41",
                "nit" => "900156264",
            ],
            [
                "id" => 3,
                "nombre" => "MUTUAL SER",
                "codigo" => "ESS207 - EPS048",
                "nit" => "806008394",
            ],
            [
                "id" => 4,
                "nombre" => "SALUD MIA",
                "codigo" => "EPS046",
                "nit" => "900914254",
            ],
            [
                "id" => 5,
                "nombre" => "ALIANSALUD EPS",
                "codigo" => "EPS001",
                "nit" => "830113831",
            ],
            [
                "id" => 6,
                "nombre" => "SALUD TOTAL EPS S.A.",
                "codigo" => "EPS002",
                "nit" => "800130907",
            ],
            [
                "id" => 7,
                "nombre" => "EPS SANITAS",
                "codigo" => "EPS005",
                "nit" => "800251440",
            ],
            [
                "id" => 8,
                "nombre" => "EPS SURA",
                "codigo" => "EPS010",
                "nit" => "800088702",
            ],
            [
                "id" => 9,
                "nombre" => "FAMISANAR",
                "codigo" => "EPS017",
                "nit" => "830003564",
            ],
            [
                "id" => 10,
                "nombre" => "SERVICIO OCCIDENTAL DE SALUD EPS SOS",
                "codigo" => "EPS018",
                "nit" => "805001157",
            ],
            [
                "id" => 11,
                "nombre" => "COMFENALCO VALLE",
                "codigo" => "EPS012",
                "nit" => "890303093",
            ],
            [
                "id" => 12,
                "nombre" => "COMPENSAR EPS",
                "codigo" => "EPS008",
                "nit" => "860066942",
            ],
            [
                "id" => 13,
                "nombre" => "EPM - EMPRESAS PUBLICAS DE MEDELLIN",
                "codigo" => "EAS016",
                "nit" => "890904996",
            ],
            [
                "id" => 14,
                "nombre" => "FONDO DE PASIVO SOCIAL DE FERROCARRILES NACIONALES DE COLOMBIA",
                "codigo" => "EAS027",
                "nit" => "800112806",
            ],
            [
                "id" => 15,
                "nombre" => "CAJACOPI ATLANTICO",
                "codigo" => "CCF055",
                "nit" => "890102044",
            ],
            [
                "id" => 16,
                "nombre" => "CAPRESOCA",
                "codigo" => "EPS025",
                "nit" => "891856000",
            ],
            [
                "id" => 17,
                "nombre" => "COMFACHOCO",
                "codigo" => "CCF102",
                "nit" => "891600091",
            ],
            [
                "id" => 18,
                "nombre" => "COMFAORIENTE",
                "codigo" => "CCF050",
                "nit" => "890500675",
            ],
            [
                "id" => 19,
                "nombre" => "EPS FAMILIAR DE COLOMBIA",
                "codigo" => "CCF033",
                "nit" => "901543761",
            ],
            [
                "id" => 20,
                "nombre" => "ASMET SALUD",
                "codigo" => "ESS062",
                "nit" => "900935126",
            ],
            [
                "id" => 21,
                "nombre" => "EMSSANAR E.S.S.",
                "codigo" => "ESS118",
                "nit" => "901021565",
            ],
            [
                "id" => 22,
                "nombre" => "CAPITAL SALUD EPS-S",
                "codigo" => "EPSS34",
                "nit" => "900298372",
            ],
            [
                "id" => 23,
                "nombre" => "SAVIA SALUD EPS",
                "codigo" => "EPSS40",
                "nit" => "900604350",
            ],
            [
                "id" => 24,
                "nombre" => "DUSAKAWI EPSI",
                "codigo" => "EPSI01",
                "nit" => "824001398",
            ],
            [
                "id" => 25,
                "nombre" => "ASOCIACION INDIGENA DEL CAUCA EPSI",
                "codigo" => "EPSI03",
                "nit" => "817001773",
            ],
            [
                "id" => 26,
                "nombre" => "ANAS WAYUU EPSI",
                "codigo" => "EPSI04",
                "nit" => "839000495",
            ],
            [
                "id" => 27,
                "nombre" => "MALLAMAS EPSI",
                "codigo" => "EPSI05",
                "nit" => "837000084",
            ],
            [
                "id" => 28,
                "nombre" => "PIJAOS SALUD EPSI",
                "codigo" => "EPSI06",
                "nit" => "809008362",
            ]
        ];

        DB::table('eps')->insert($eps);
    }
}