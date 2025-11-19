<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cochera;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CocheraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $idempresa = Auth::user()->EMP_Id;
        if ($request->ajax()) {
            if(Auth::user()->esAdministrador()){
                $data = DB::table('cocheras as c')
                ->join('empresas as e', 'e.id', '=', 'c.EMP_Id')
                ->select('c.id','c.nombre','c.direccion','e.nombre as empresa')
                ->where('c.estado','activa')
                ->get();
            }else{
                $data = DB::table('cocheras as c')
                ->join('empresas as e', 'e.id', '=', 'c.EMP_Id')
                ->select('c.id','c.nombre','c.direccion','e.nombre as empresa')
                ->where('c.EMP_Id',$idempresa)
                ->where('c.estado','activa')
                ->get();

            }
            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action1', function ($row) {
                    $btn = '<a data-toggle="tooltip"  data-identificador="' . $row->id . '" data-original-title="Edit" class="edit btn btn-info btn-sm editCochera" ><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('action2', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteCochera"><i class="fa fa-trash"></i></a>';

                    return $btn;
                })
                ->addColumn('action3', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Ver" class="btn btn-warning btn-sm eyeCochera"><i class="fa fa-eye" aria-hidden="true"></i></a>';

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
        return view('operacion.cochera.index',compact('empresas'));
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
        try {
            DB::beginTransaction();

            $query=Cochera::where('nombre','=',$request->get('nombre'))->where('EMP_Id', $request->get('EMP_Id'))->get();
            if($query->count()!=0) //si lo encuentra, osea si no esta vacia
            {
                
                return response()->json(['error' => 'Cochera ya registrado'], 401);                   
            }
            else{
                $cochera=new Cochera();
                $cochera->EMP_Id=$request->EMP_Id;
                $cochera->nombre=$request->nombre;
                $cochera->direccion=$request->direccion;
                $cochera->latitud=$request->latitud;
                $cochera->longitud=$request->longitud;
                $cochera->save();                   
            }

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }        
        return response()->json(['success' => 'Cochera Registrado Exitosamente!',compact('cochera')]); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cochera = DB::table('cocheras as c')
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
        $cochera = Cochera::find($id);
        return response()->json(['data' => $cochera]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $cochera = Cochera::find($id);
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

        return response()->json(['success' => 'Cochera Editado Exitosamente.',compact('cochera')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cochera = Cochera::find($id);
        $cochera->estado = 'inactiva';
		$cochera->update();

        return response()->json(['success' => 'Cochera Eliminado Exitosamente.']);
    }
}
