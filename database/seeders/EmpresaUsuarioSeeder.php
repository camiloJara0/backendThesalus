<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\User;
use App\Models\InformacionUser;
use Illuminate\Support\Facades\Hash;


class EmpresaUsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear empresa por defecto
        $empresa = Empresa::create([
            'nombre' => 'store one',
            'no_identificacion' => '900123456',
            'DV' => '5',
            'direccion' => 'Calle Ficticia #123',
            'municipio' => 'Cali',
            'pais' => 'Colombia',
            'telefono' => '5551234',
            'lenguaje' => 'es',
            'tipoDocumento' => 'NIT',
            'tipoEntorno' => 'Producción',
            'tipoMoneda' => 'COP',
            'tipoOperacion' => 'Comercial',
            'tipoOrganizacion' => 'Privada',
            'tipoRegimen' => 'Simplificado',
            'tipoResponsabilidad' => 'IVA',
            'impuesto' => '19',
            'registroMercantil' => '123456789',
            'logo' => 'logo.png',
            'logoLogin' => 'logo_login.png',
            'JPG' => 'empresa.jpg',

        ]);

        // Crear usuario asociado a esa empresa
        $infoUser = InformacionUser::create([
            'name'         => 'Administrador General',
            'No_document'  => '1000000000',
            'type_doc'     => 'cedula',
            'celular'      => '3001234567',
            'telefono'     => '572123456',
            'nacimiento'   => '1980-01-01',
            'direccion'    => 'Calle Principal #123',
            'municipio'    => 'CALI',
            'departamento' => 'VALLE DEL CAUCA',
            'barrio'       => 'CENTRO',
            'zona'         => 'Urbana'
        ]);

        // Crear usuario asociado a esa empresa
        User::create([
            'id_empresa' => $empresa->id, // clave foránea
            'id_infoUsuario' => $infoUser->id, // clave foránea
            'correo' => 'admin@demo.com',
            'contraseña' => Hash::make('password'),
            'rol' => 'Admin',
            // otros campos si tu modelo los necesita
        ]);

    }
}
