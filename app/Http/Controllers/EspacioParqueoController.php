<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EspacioParqueo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EspacioParqueoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
			$data = DB::table('espacios_parqueo as ep')
            ->join('cocheras as c', 'c.id', '=', 'ep.COC_Id')
            ->leftJoin('tipos_vehiculos as tv', 'tv.id', '=', 'ep.TIV_Id')
            ->select('ep.*','c.nombre as Cochera', 'tv.nombre as TipoVehiculo')
            ->where('ep.estado',1)->get();
            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action1', function ($row) {
                    $btn = '<a data-toggle="tooltip"  data-identificador="' . $row->id . '" data-original-title="Edit" class="edit btn btn-info btn-sm editEspacioParqueo" ><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('action2', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteEspacioParqueo"><i class="fa fa-trash"></i></a>';

                    return $btn;
                })
                ->addColumn('action3', function ($row) {
                    $btn = '';

                    return $btn;
                })
               
                ->rawColumns(['action1','action2','action3'])
                ->make(true);
        }
        $cocheras = DB::table('cocheras')->where('estado',1)->get();
        $tipos_vehiculos = DB::table('tipos_vehiculos')->where('estado',1)->get();
        return view('operacion.espacios_parqueo.index',compact('cocheras','tipos_vehiculos'));
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

            $query=EspacioParqueo::where('codigo','=',$request->get('codigo'))->where('COC_Id', $request->get('COC_Id'))->get();
            if($query->count()!=0) //si lo encuentra, osea si no esta vacia
            {
                
                return response()->json(['error' => 'EspacioParqueo ya registrado'], 401);                   
            }
            else{
                $espacioparqueo=new EspacioParqueo();
                $espacioparqueo->COC_Id=$request->COC_Id;
                $espacioparqueo->codigo=$request->codigo;
                $espacioparqueo->TIV_Id=$request->TIV_Id;
                $espacioparqueo->save();                   
            }

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }        
        return response()->json(['success' => 'EspacioParqueo Registrado Exitosamente!',compact('espacioparqueo')]); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $espacioparqueo = DB::table('espacios_parqueo as c')
            ->join('empresas as e', 'e.id', '=', 'c.EMP_Id')
            ->select('c.*','e.nombre as empresa')
            ->where('c.id',$id)->first();

        return response()->json(['data' => $espacioparqueo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $espacioparqueo = EspacioParqueo::find($id);
        return response()->json(['data' => $espacioparqueo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $espacioparqueo = EspacioParqueo::find($id);
            $espacioparqueo->COC_Id=$request->COC_Id;
            $espacioparqueo->codigo=$request->codigo;
            $espacioparqueo->TIV_Id=$request->TIV_Id;
            $espacioparqueo->update();

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }

        return response()->json(['success' => 'EspacioParqueo Editado Exitosamente.',compact('espacioparqueo')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $espacioparqueo = EspacioParqueo::find($id);
        $espacioparqueo->estado = 0;
		$espacioparqueo->update();

        return response()->json(['success' => 'EspacioParqueo Eliminado Exitosamente.']);
    }
}
