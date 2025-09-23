<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controller;

class userController extends Controller
{
    function __construct() {
        $this->middleware('permission:ver-user|crear-user|editar-user|eliminar-user',['only' => ['index']]);
        $this->middleware('permission:crear-user',['only' => ['create','store']]);
        $this->middleware('permission:editar-user', ['only' => ['edit','update']]);
        $this->middleware('permission:eliminar-user', ['only'=> ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            //Encriptar contraseña
            $fieldHash = Hash::make($request->password);//Hash proporciona un hash Bcrypt seguro para almacenar las contraseñas de los usuarios, básicamente encripta las contraseñas antes de guardarla en la base de datos

            //Guardar en password la contraseña encriptada
            $request->merge(['password' => $fieldHash]);//El método merge() se utiliza para agregar o modificar datos en un objeto
            
            //Crear el nuevo usuario con la contraseña ya encriptada
            $user = User::create($request->validated());//OBS: Acá el del video hizo con all(), por lo tanto se podría añadir datos maliciosos o inesperados
            
            //Asignar su rol
            //$user->assignRole($request->role);//como el susuario solo puede tener un rol no utilizamos el assignRole ya que este te permite tener varios roles
            $user->syncRoles($request->role);//Usamos syncRoles() si el usuario debe tener solo un rol a la vez (caso más común en muchos sistemas).

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
        }

        return redirect()->route('users.index')->with('success','Usuario registrado');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        
        $user->load('roles');
        
        $roles = Role::all();//Utilizo all() cuando tengo muy pocos registros(menos de 50 registros)

        return view('user.edit', compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            // Obtener los datos validados convirtiendolos en un array 
            $data = $request->validated();//Convierte el objeto $request en un array con $request->all() o $request->validated() (mejor opción si usas FormRequest con reglas de validación)
            
            //// Si no se ingresó nueva contraseña, la quitamos
            if(empty($data['password'])) {
                unset($data['password']);// Quita el campo 'password' si está vacío
            } else {

                //Encriptar el nuevo password y guardarlo dentro del campo password de $data
                $data['password'] = Hash::make($data['password']);//Hash proporciona un hash Bcrypt seguro para almacenar las contraseñas de los usuarios, básicamente encripta las contraseñas antes de guardarla en la base de datos
            }
            
            /* este sería otro caso de utilizar en el que se elimina el password del array
            if (empty($request->password)) {
                $data = Arr::except($data, ['password']);
            } else {
                $data['password'] = Hash::make($request->password);//Encriptar el password con el Hash y guardarlo dentro del campo password de $data
            }*/

            // Actualizar usuario con los datos procesados
            $user->update($data);

            //Actualizar el rol
            $user->syncRoles([$request->role]);

            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();

            //    HAY QUE VER ESTA PARTE SI LO MANTENGO O NO
            //Log::error('Error al actualizar usuario: ' . $e->getMessage());
            //return back()->withErrors('Ocurrió un error inesperado. Intenta más tarde.');

        }

        return redirect()->route('users.index')->with('success','Usuario editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        //Eliminar el rol
        $rolUser = $user->getRoleNames()->first();
        $user->removeRole($rolUser);

        //Eliminar usuario
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado');
    }
}
