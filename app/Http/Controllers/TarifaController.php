<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tarifa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
			/* $data = DB::table('marcaciones as m')
            ->join('vehiculos as v', 'v.id', '=', 'm.VEH_Id')
            ->join('espacios_parqueo as e', 'e.id', '=', 'm.ESP_Id')
            ->select(
                'm.*',
                'v.placa as vehiculo_placa',
                'e.nombre as espacio_nombre'
            )
            ->get();   */      
            $data = DB::table('tarifas as t')
            ->join('cocheras as c', 'c.id', '=', 't.COC_Id')
            ->join('tipos_vehiculos as tv', 'tv.id', '=', 't.TIV_Id')
            ->select(
                't.*',
                'c.nombre as Cochera',
                'tv.nombre as TipoVehiculo'
            )
            ->where('t.estado',1)
            ->get();

            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action1', function ($row) {
                    $btn = '<a data-toggle="tooltip"  data-identificador="' . $row->id . '" data-original-title="Edit" class="edit btn btn-info btn-sm editTarifa" ><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('action2', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteTarifa"><i class="fa fa-trash"></i></a>';

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
        return view('gestion.tarifa.index',compact('cocheras','tipos_vehiculos'));
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

            $query=Tarifa::where('TIV_Id','=',$request->get('TIV_Id'))->where('COC_Id', $request->get('COC_Id'))->get();
            if($query->count()!=0) //si lo encuentra, osea si no esta vacia
            {
                
                return response()->json(['error' => 'Tarifa ya registrado'], 401);                   
            }
            else{
                $tarifa=new Tarifa();
                $tarifa->COC_Id=$request->COC_Id;
                $tarifa->TIV_Id=$request->TIV_Id;
                $tarifa->precio_hora=$request->precio_hora;
                $tarifa->precio_dia=$request->precio_dia;
                $tarifa->precio_mes=$request->precio_mes;
                $tarifa->moneda=$request->moneda;
                $tarifa->save();                   
            }

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }        
        return response()->json(['success' => 'Tarifa Registrado Exitosamente!',compact('tarifa')]); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tarifa = DB::table('tarifas as c')
            ->join('empresas as e', 'e.id', '=', 'c.EMP_Id')
            ->select('c.*','e.nombre as empresa')
            ->where('c.id',$id)->first();

        return response()->json(['data' => $tarifa]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tarifa = Tarifa::find($id);
        return response()->json(['data' => $tarifa]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $tarifa = Tarifa::find($id);
            $tarifa->COC_Id=$request->COC_Id;
            $tarifa->TIV_Id=$request->TIV_Id;
            $tarifa->precio_hora=$request->precio_hora;
            $tarifa->precio_dia=$request->precio_dia;
            $tarifa->precio_mes=$request->precio_mes;
            $tarifa->moneda=$request->moneda;
            $tarifa->update();

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }

        return response()->json(['success' => 'Tarifa Editado Exitosamente.',compact('tarifa')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tarifa = Tarifa::find($id);
        $tarifa->estado = 0;
		$tarifa->update();

        return response()->json(['success' => 'Tarifa Eliminado Exitosamente.']);
    }
}
