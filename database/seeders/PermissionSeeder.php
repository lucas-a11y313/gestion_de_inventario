<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            //categoria
            'ver-categoria',
            'crear-categoria',
            'editar-categoria',
            'eliminar-categoria',

            //marca
            'ver-marca',
            'crear-marca',
            'editar-marca',
            'eliminar-marca',

            //adquisicion
            'ver-adquisicion',
            'crear-adquisicion',
            'mostrar-adquisicion',
            'eliminar-adquisicion',

            //solicitud
            'ver-solicitud',
            'crear-solicitud',
            'mostrar-solicitud',
            'eliminar-solicitud',

            //proyecto
            'ver-proyecto',
            'crear-proyecto',
            'editar-proyecto',
            'mostrar-proyecto',
            'eliminar-proyecto',

            //producto
            'ver-producto',
            'crear-producto',
            'editar-producto',
            'eliminar-producto',

            //inventarioBP
            'ver-inventarioBP',
            'crear-inventarioBP',
            'editar-inventarioBP',
            'mostrar-inventarioBP',

            //proveedor
            'ver-proveedor',
            'crear-proveedor',
            'editar-proveedor',
            'eliminar-proveedor',

            //Roles
            'ver-role',
            'crear-role',
            'editar-role',
            'eliminar-role',

            //Users
            'ver-user',
            'crear-user',
            'editar-user',
            'eliminar-user',

            //Ubicaciones
            'ver-ubicacion',
            'crear-ubicacion',
            'editar-ubicacion',
            'eliminar-ubicacion',
        ];

        foreach($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
    }
}
