<?php

namespace App\Http\Controllers;

use App\Models\TipoVehiculo;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('tipos_vehiculos')->where('estado', 1)->get();
            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action1', function ($row) {
                    $btn = '<a data-toggle="tooltip"  data-identificador="' . $row->id . '" data-original-title="Edit" class="edit btn btn-info btn-sm editEmpresa" ><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('action2', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteEmpresa"><i class="fa fa-trash"></i></a>';

                    return $btn;
                })
                ->addColumn('action3', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Ver" class="btn btn-warning btn-sm eyeEmpresa"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                    return $btn;
                })

                ->rawColumns(['action1', 'action2', 'action3'])
                ->make(true);
        }
        return view('operacion.tipo_vehiculo.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

         

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $query=TipoVehiculo::where('nombre','=',$request->get('nombre'))->get();
            if($query->count()!=0) //si lo encuentra, osea si no esta vacia
            {
                
                return response()->json(['error' => 'Tipo Vehículo ya registrado'], 401);                   
            }
            else{
                $tipoVehiculo = new TipoVehiculo();
                $tipoVehiculo->nombre = $request->nombre;
                $tipoVehiculo->tamaño = $request->tamaño;
                $tipoVehiculo->descripcion = $request->descripcion;
                $tipoVehiculo->save();

                $path = 'storage/archivos/tipoVehiculo/';
                $file = $request->file('file');
                if($file){
                    $extension = $file->getClientOriginalExtension();
                    $fileName =  $tipoVehiculo->id . '.' . $extension;
                    $upload = $file->move($path, $fileName);
                    
                    $updateempresa = DB::table('tipos_vehiculos')
                    ->where('id', $tipoVehiculo->id)
                    ->update(['logo_url' => $fileName]);
                }
                   
            }

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }        
        return response()->json(['success' => 'Tipo Vehículo Registrado Exitosamente!',compact('tipoVehiculo')]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tipoVehiculo = TipoVehiculo::find($id);
        $imagen="";
        if($tipoVehiculo->imagen){
            $imagen = '/storage/archivos/tipoVehiculo/'.$tipoVehiculo->imagen.'?' . uniqid();
        }
        return response()->json(['data' => $tipoVehiculo,'imagen'=> $imagen]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $tipoVehiculo = TipoVehiculo::find($id);
            $tipoVehiculo->nombre = $request->nombre;
            $tipoVehiculo->tamaño = $request->tamaño;
            $tipoVehiculo->descripcion = $request->descripcion;
            $tipoVehiculo->update();

            $path = 'storage/archivos/tipoVehiculo/';
            $file = $request->file('file');
            if($file){
                $extension = $file->getClientOriginalExtension();
                $fileName =  $tipoVehiculo->id . '.' . $extension;
                $upload = $file->move($path, $fileName);
                
                $tipoVehiculo = DB::table('tipos_vehiculos')
                ->where('id', $tipoVehiculo->id)
                ->update(['imagen' => $fileName]);
            }

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }

        return response()->json(['success' => 'Tipo Vehículo Editado Exitosamente.',compact('tipoVehiculo')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tipoVehiculo = TipoVehiculo::find($id);
        $tipoVehiculo->estado = 0;
		$tipoVehiculo->update();

        return response()->json(['success' => 'Tipo Vehículo Eliminado Exitosamente.']);
    }
}
