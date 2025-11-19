<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Stringable;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showlogin()
    {
        
        return view('login');
    }

    public function verificarlogin(Request $request){
         $data=request()->validate([
             'email'=>'required',
             'password'=>'required'
        ],
         [
             'email.required'=>'Ingrese email',
             'password.required'=>'Ingresa Contraseña'
         ]);
         if(Auth::attempt($data)) {
             $con='ok';
         }
        $email=$request->get('email');
        $query=User::where('email', '=', $email)->get();
        
        if($query->count()!=0) {
            if($query[0]->estadousuario == 1)
            {
                $hashp=$query[0]->password;
                $password=$request->get('password');
                if(password_verify($password, $hashp)) {
                    //return redirect()->route('home',compact('email'));
                    //return Redirect::to('/Home');
                    
                    return response()->json(['success' => 'Inicio de Sesión Exitoso.']);
                } else {
                    // return back()->withErrors(['password'=>'Contraseña no valida'])
                    // ->withInput(request(['email', 'password']));
                    return response()->json(['error' => 'Contraseña no valida'], 401);
                }
            }
            else{
                // return back()->withErrors(['email'=>'Usuario deshabilitado'])
                //     ->withInput(request(['email','password']));
                return response()->json(['error' => 'Usuario deshabilitado'], 401);
                }
        } else {
            // return back()->withErrors(['email'=>'Usuario no valido'])
            // ->withInput(request(['email','password']));
            return response()->json(['error' => 'Usuario no valido'], 401);
        }
    }
    public function salir()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    const PAGINATION=10;
    public function index(Request $request)
    {  
        $idempresa = Auth::user()->EMP_Id;

        if ($request->ajax()) {            
            if(Auth::user()->esAdministrador()){
                $data = DB::table('users as u')
                ->where('u.estadousuario','=',1)
                ->select('u.*')
                ->get();
            }else{
                $data = DB::table('users as u')
                ->where('u.estadousuario','=',1)
                ->where('u.EMP_Id','=',$idempresa)
                ->select('u.*')
                ->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action1', function ($row) {
                    $btn = '<a data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-info btn-sm editUsuario" ><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('action2', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteUsuario"><i class="fa fa-trash"></i></a>';

                    return $btn;
                })
                ->addColumn('action3', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Ver" class="btn btn-warning btn-sm eyeUsuario"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action1','action2','action3'])
                ->make(true);
        }
        if(Auth::user()->esAdministrador()){
            $empresas = DB::table('empresas')->where('estado',1)->get();
        }else{  
            $empresas = DB::table('empresas')->where('id',$idempresa)->where('estado',1)->get();
        }
        return view('seguridad.users.index',compact('empresas'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $query=User::where('email','=',$request->get('email'))->get();
        if($query->count()!=0) //si lo encuentra, osea si no esta vacia
        {
            // return back()->withErrors(['email'=> 'Usuario ya registrado'])
            // ->withInput(request(['name','email','password','confipassword']));
            return response()->json(['error' => 'Usuario ya registrado'], 401);                   
        }
        else{
            $password=$request->get('password');
            $confipassword=$request->get('confipassword');

            if($password == $confipassword) {
                $usuario = new User();
                $usuario->name = $request->name;
                $usuario->email = $request->email;
                $usuario->password = Hash::make($request->password);
                $usuario->remember_token= Str::random(10);
                $usuario->estadousuario = 1;
                $usuario->tipousuario = 0;
                $usuario->EMP_Id = $request->EMP_Id;
                $usuario->save();

                // return redirect()->route('usuario.index')->with('datos','Usuario agregado ...!');
                return response()->json(['success' => 'Usuario Registrado Exitosamente!',compact('usuario')]);
            }
            else{
            //     return back()->withErrors(['password'=>'Las contraseñas no coinciden','confipassword'=>'Las contraseñas no coinciden'])
            // ->withInput(request(['name','email','password','confipassword']));
                return response()->json(['error' => 'Las contraseñas no coinciden'], 401); 
            
            }
            
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = User::find($id);
        return response()->json(['data' => $usuario]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roles = Role::all();
        $usuario = User::find($id);
        $rolesAsignados = $usuario->roles;
        
        return view('seguridad.users.edit',compact('usuario','rolesAsignados','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data=request()->validate([
            'name'=>'required',
        ],
        [
            'name.required'=>'Ingrese nombre',
            
        ]);
        $usuario =  User::find($id);
        $usuario->name = $request->name;
        
        // Obtengo los roles seleccionados desde la solicitud
        $rolesSeleccionados = $request->input('roles');

        // Asigna los roles seleccionados al usuario
        $usuario->roles()->sync($rolesSeleccionados);
        //$usuario->roles()->sync($rolesSeleccionados); hace lo mismo q el de arriba

        $usuario->save();

        return redirect()->route('usuario.index')->with('datos','Usuario actualizado y roles asignados correctamente ...!');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::find($id);
        $usuario->estadousuario = 0;
        $usuario->save();
        //return redirect()->route('usuario.index')->with('datos','Usuario eliminado ...!');
        return response()->json(['success' => 'Usuario Eliminado Exitosamente.']);
    }

}
