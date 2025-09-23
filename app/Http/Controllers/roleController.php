<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controller;

class roleController extends Controller
{
    function __construct() {
        $this->middleware('permission:ver-role|crear-role|editar-role|eliminar-role',['only' => ['index']]);
        $this->middleware('permission:crear-role',['only' => ['create','store']]);
        $this->middleware('permission:editar-role', ['only' => ['edit','update']]);
        $this->middleware('permission:eliminar-role', ['only'=> ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$roles = Role::with('permissions')->get();
        //dd($roles);
        
        $roles = Role::all();//Utilizo all() cuando tengo muy pocos registros(menos de 50 registros)
        return view('role.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permisos = Permission::all();//Utilizo all() cuando tengo muy pocos registros(menos de 50 registros)
        
        return view('role.create',compact('permisos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required'
        ]);

        try{
            DB::beginTransaction();
            //Crear rol
            $rol = Role::create(['name' => $request->name]);
    
            //Asignar permisos
            $permissionsNames = Permission::whereIn('id', $request->permission)->pluck('name')->toArray();//se obtienen los nombres de los permisos a partir de los IDs
            
            $rol->syncPermissions($permissionsNames);//syncPermissions()Es un método del paquete Spatie Laravel Permission. Sirve para asignar una lista de permisos a un rol. Internamente, actualiza la tabla intermedia role_has_permissions.
            //dd($rol);
            DB::commit();

        } catch(Exception $e){
            //dd($e);
            DB::rollBack();

        }


        return redirect()->route('roles.index')->with('success','Rol registrado');
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
    public function edit(Role $role)
    {
        $role->load('permissions');

        $permisos = Permission::all();//Utilizo all() cuando tengo muy pocos registros(menos de 50 registros)

        return view('role.edit', compact('role','permisos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {

        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'permission' => 'required'
        ]);

        try{
            DB::beginTransaction();
            
            //Actualizar rol
            //Role::where('id',$role->id)->update(['name' => $request->name]);
            $role->name = $request->name;
            $role->save();

            //Traer los nombres de los permisos através de los IDs
            $permissionsName = Permission::whereIn('id',$request->permission)->pluck('name')->toArray();

            //Actualizar permisos 
            $role->syncPermissions($permissionsName);

            DB::commit();

        } catch(Exception $e){
            //dd($e);
            DB::rollBack();

        }

        return redirect()->route('roles.index')->with('success', 'Rol editado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Role::where('id',$id)->delete();//si el id del rol es igual al $id que le enviamos se elimina dicho rol

        return redirect()->route('roles.index')->with('success', 'Rol eliminado');
    }
}
