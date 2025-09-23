<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* el insert te permite ingresar varios usuarios a la vez
        User::insert([
            [
                'name' => 'Sak Noel',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('password')
            ]
        ]);
        */

        // el create solo te permite crear un usuario por vez
        $user = User::create([
            'name' => 'Lucas',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password')
        ]);

        //Usuario administrador
        $rol = Role::create(['name' => 'administrador']);//Creamos el rol de administrador
        $permisos = Permission::pluck('id','id')->all();//traemos todos los permisos y les asignamos a la variable $permisos
        $rol->syncPermissions($permisos);//los permisos se los estoy asignando al rol de administrador
        //$user = User::find(1);//traigo el usuario con id 1 y le asigno a $user
        $user->assignRole('administrador');//al usuario que está en $user se le asigna el rol de administrador

    }
}
