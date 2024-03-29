<?php

namespace App\Http\Controllers\Usuarios;

use App\DataTables\User\PorRolDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Usuarios\RqActualizarUsuario;
use App\Http\Requests\Usuarios\RqEliminarUsuario;
use App\Http\Requests\Usuarios\RqGuardarUsuario;
use App\Models\Configuracion;
use App\Models\User;
use App\Notifications\RegistroUsuarioNoty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Usuarios']);
    }
    public function index(UsersDataTable $dataTable)
    {
        $data = array('roles' => Role::whereNotIn('name',['SuperAdmin','SuperAdmin','SiteAdmin'])->get());
        return $dataTable->render('usuarios.index',$data);
    }
    public function usuariosPoRol(PorRolDataTable $dataTable, $nombreRol)
    {
        
        try {
            
            $roles=Role::whereNotIn('name',['SuperAdmin','SuperAdmin','SiteAdmin'])->get();
            if($nombreRol!='INACTIVOS'){
                $role = Role::findByName($nombreRol);
                return $dataTable->with('rol',$role->name)->render('usuarios.index',['roles'=>$roles]);
            }else{
                return $dataTable->with('estado','INACTIVO')->render('usuarios.index',['roles'=>$roles]);
            }
            


        } catch (\Exception $th) {
            return abort(404);
        }
    }

    public function nuevo()
    {
        $roles=Role::whereNotIn('name',['SuperAdmin'])->get();
        return view('usuarios.nuevo',['roles'=>$roles]);
    }
    public function guardar(RqGuardarUsuario $request)
    {
        try {
            DB::beginTransaction();
            $user=new User();
            $user->apellidos=$request->apellidos;
            $user->name=$request->email;
            $user->nombres=$request->nombres;
            $user->telefono=$request->telefono;
            $user->documento=$request->documento;
            $user->cuidad=$request->cuidad;
            $user->direccion=$request->direccion;
            $user->descripcion=$request->descripcion;
            $user->email=$request->email;
            $user->estado=$request->estado;

            $password=Str::random(15);
            $user->password=Hash::make($password);
            $user->user_create=Auth::user()->id;
            $user->save();
            if ($request->hasFile('foto')) {
                $archivo=$request->file('foto');
                if ($archivo->isValid()) {
                    $path = Storage::putFileAs(
                        'public/avatars', $archivo, $user->id.'.'.$archivo->extension()
                    );
                    $user->foto=$path;
                }
            }

            if ($request->hasFile('firma')) {
                $archivo=$request->file('firma');
                if ($archivo->isValid()) {
                    $path = Storage::putFileAs(
                        'public/firmas', $archivo, $user->id.'.'.$archivo->extension()
                    );
                    $user->firma=$path;
                }
            }


            $conf=new Configuracion();
            $conf->user_id=$user->save();
            $conf->save();
            $user->save();
            $user->assignRole($request->roles);
            DB::commit();
            $user->notify(new RegistroUsuarioNoty($user,$password));
            request()->session()->flash('success','Usuario guardado, se envió información de acceso a '.$user->email);
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('info','Error al guardar usuario');
        }
        return redirect()->route('usuarios');
    }

    public function editar($id)
    {
        $user=User::find($id);
        
        $roles=Role::whereNotIn('name',['SuperAdmin'])->get();
        return view('usuarios.editar',['user'=>$user,'roles'=>$roles]);
    }

    public function actualizar(RqActualizarUsuario $request)
    {
        
        try {
            DB::beginTransaction();
            $user=User::find($request->id);
            $user->apellidos=$request->apellidos;
            $user->name=$request->email;
            $user->nombres=$request->nombres;
            $user->telefono=$request->telefono;
            $user->documento=$request->documento;
            $user->cuidad=$request->cuidad;
            $user->direccion=$request->direccion;
            $user->descripcion=$request->descripcion;
            $user->email=$request->email;
            $user->estado=$request->estado;

            if ($request->hasFile('foto')) {
                $archivo=$request->file('foto');
                if ($archivo->isValid()) {
                    Storage::delete($user->foto);
                    $path = Storage::putFileAs(
                        'public/avatars', $archivo, $user->id.'.'.$archivo->extension()
                    );
                    $user->foto=$path;
                }
            }

            if ($request->hasFile('firma')) {
                $archivo=$request->file('firma');
                if ($archivo->isValid()) {
                    Storage::delete($user->firma);
                    $path = Storage::putFileAs(
                        'public/firmas', $archivo, $user->id.'.'.$archivo->extension()
                    );
                    $user->firma=$path;
                }
            }


            $user->user_update=Auth::user()->id;
            $user->save();
            $user->syncRoles($request->roles);
            DB::commit();
            request()->session()->flash('success','Usuario actualizado');
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('info','Error al actualizar usuario');
        }
        return redirect()->route('usuarios');
    }

    public function eliminar(RqEliminarUsuario $request)
    {
        
        try {
            DB::beginTransaction();
            $user=User::find($request->id);
            if($user->delete()){
                Storage::delete($user->foto);
                Storage::delete($user->firma);
            }
            DB::commit();
            request()->session()->flash('success','Usuario eliminado');
        } catch (\Throwable $th) {
            DB::rollback();
            request()->session()->flash('info','Error al eliminar usuario, '.$th->getMessage());
        }
        return redirect()->route('usuarios');
    }
    public function ingresar(Request $request,$id)  {
        $user=User::find($id);
        Auth::loginUsingId($user->id);
        request()->session()->flash('success','Ingresado con usuario '.$user->email);
        return redirect()->route('welcome');
    }
}
