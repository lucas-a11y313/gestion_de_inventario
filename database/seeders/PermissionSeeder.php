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
            
            //cliente
            'ver-cliente',
            'crear-cliente',
            'editar-cliente',
            'eliminar-cliente',

            //compra
            'ver-compra',
            'crear-compra',
            'mostrar-compra',
            'eliminar-compra',

            //venta
            'ver-venta',
            'crear-venta',
            'mostrar-venta',
            'eliminar-venta',

            //producto
            'ver-producto',
            'crear-producto',
            'editar-producto',
            'eliminar-producto',

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
        ];

        foreach($permisos as $permiso) {
            Permission::create(['name' => $permiso]);
        }
    }
}
