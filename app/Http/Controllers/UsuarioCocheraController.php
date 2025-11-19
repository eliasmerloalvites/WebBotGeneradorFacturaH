<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cochera;
use App\Models\UsuarioCochera;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class UsuarioCocheraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $idempresa = Auth::user()->EMP_Id;
        if ($request->ajax()) {
            if(Auth::user()->esAdministrador()){
                $data = DB::table('usuario_cocheras as uc')
                ->join('users as u', 'u.id', '=', 'uc.USU_Id')
                ->join('cocheras as c', 'c.id', '=', 'uc.COC_Id')
                ->join('empresas as e', 'e.id', '=', 'c.EMP_Id')
                ->select('uc.*','u.id','u.name','c.id','c.nombre','c.direccion','e.nombre as empresa')
                ->get();
            }else{
                $data = DB::table('usuario_cocheras as uc')
                ->join('users as u', 'u.id', '=', 'uc.USU_Id')
                ->join('cocheras as c', 'c.id', '=', 'uc.COC_Id')
                ->join('empresas as e', 'e.id', '=', 'c.EMP_Id')
                ->select('uc.*','u.id','u.name','c.id','c.nombre','c.direccion','e.nombre as empresa')
                ->where('e.id',$idempresa)
                ->get();
            }
            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action1', function ($row) {
                    $btn = '<a data-toggle="tooltip"  data-identificador="' . $row->COC_Id .'-'.$row->USU_Id. '" data-original-title="Edit" class="edit btn btn-info btn-sm editUsuarioCochera" ><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('action2', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->COC_Id .'-'.$row->USU_Id. '" data-original-title="Delete" class="btn btn-danger btn-sm deleteUsuarioCochera"><i class="fa fa-trash"></i></a>';

                    return $btn;
                })
                ->addColumn('action3', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->COC_Id .'-'.$row->USU_Id. '" data-original-title="Ver" class="btn btn-warning btn-sm eyeUsuarioCochera"><i class="fa fa-eye" aria-hidden="true"></i></a>';

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

        $admin = Auth::user()->esAdministrador();
        
        return view('operacion.usuario_cochera.index',compact('empresas','admin'));
    }

    public function userall(Request $request)
    {
        $idempresa = Auth::user()->EMP_Id;
        $idCochera = $request->input('COC_Id');        
        if ($request->ajax() ) {
            $EMP_Id = $request->input('EMP_Id'); 
            $users = DB::table('usuario_cocheras')->where('COC_Id',$idCochera)->pluck('USU_Id');
            if(Auth::user()->esAdministrador()){
                $data = DB::table('users as u')->where('EMP_Id',$EMP_Id)->whereNotIn('id',$users)->get();
            }else{
                $data = DB::table('users as u')->where('EMP_Id',$idempresa)->whereNotIn('id',$users)->get();
            }

            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    return '<input type="checkbox" name="usuarios[]" class="usuario-check" value="'.$row->id.'" />';
                })               
                ->rawColumns(['check'])
                ->make(true);
        }
    }

    public function userscochera(Request $request)
    {
        $idCochera = $request->input('COC_Id');
        if ($request->ajax()) {
			$data = DB::table('usuario_cocheras as uc')
            ->join('users as u', 'u.id', '=', 'uc.USU_Id')
            ->select('uc.*','u.id','u.name')
            ->where('uc.COC_Id', $idCochera)
            ->get();

            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    return '<input type="checkbox" name="usuariosAsignados[]" class="usuario-asignados-check" value="'.$row->USU_Id.'" />';
                })               
                ->rawColumns(['check'])
                ->make(true);
        
        }
    }
    
    public function cocheraxempresa($id)
    {
         if(Auth::user()->esAdministrador()){
            $cocheras = Cochera::where('EMP_Id', $id)
            ->where('estado', true)
            ->select('id', 'nombre')
            ->get();
         }else{
            $cocheras = Cochera::where('EMP_Id', Auth::user()->EMP_Id)
            ->where('estado', true)
            ->select('id', 'nombre')
            ->get();
         }
        

        return response()->json($cocheras);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            if($request->type == 'asignar')
            {
                $usuarios = $request->usuarios;
                foreach($usuarios as $usuario)
                {
                    $cochera=new UsuarioCochera();
                    $cochera->COC_Id=$request->COC_Id;
                    $cochera->USU_Id=$usuario;
                    $cochera->save();
                }
            }
            else
            {
                $usuarios = $request->input('usuariosAsignados');
                foreach($usuarios as $usuario)
                {
                    UsuarioCochera::where('USU_Id', $usuario)
                    ->where('COC_Id', $request->COC_Id)
                    ->delete();
                }
            }
            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }   
        if($request->type == 'asignar')
        {
            return response()->json(['success' => 'Usuarios Asignados Exitosamente!']);
        }else{
            return response()->json(['success' => 'Usuarios Designados Exitosamente!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cochera = DB::table('usuario_cocheras as c')
            ->join('empresas as e', 'e.id', '=', 'c.EMP_Id')
            ->select('c.*','e.nombre as empresa')
            ->where('c.id',$id)->first();

        return response()->json(['data' => $cochera]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cochera = UsuarioCochera::find($id);
        return response()->json(['data' => $cochera]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $cochera = UsuarioCochera::find($id);
            $cochera->EMP_Id=$request->EMP_Id;
            $cochera->nombre=$request->nombre;
            $cochera->direccion=$request->direccion;
            $cochera->latitud=$request->latitud;
            $cochera->longitud=$request->longitud;
            $cochera->update();

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }

        return response()->json(['success' => 'UsuarioCochera Editado Exitosamente.',compact('cochera')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cochera = UsuarioCochera::find($id);
        $cochera->estado = 'inactiva';
		$cochera->update();

        return response()->json(['success' => 'UsuarioCochera Eliminado Exitosamente.']);
    }
}
