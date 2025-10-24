<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id_empresa' => null,
            'correo' => 'admin@mail.com',
            'contraseña' => Hash::make('password'),
            'rol' => 'Admin',
            // agrega otros campos si tu tabla los requiere
        ]);

    }
}
