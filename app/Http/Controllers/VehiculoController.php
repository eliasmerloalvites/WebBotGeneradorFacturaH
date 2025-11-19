<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EspacioParqueo;
use App\Models\Vehiculo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehiculoController extends Controller
{
    /**
     * Muestra una lista de vehículos.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Se obtiene la data de la tabla vehículos.
            $data = DB::table('vehiculos')->get();

            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action1', function ($row) {
                    $btn = '<a data-toggle="tooltip" data-identificador="' . $row->id .
                        '" data-original-title="Editar" class="editVehiculo btn btn-info btn-sm editVehiculo"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('action2', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id .
                        '" data-original-title="Eliminar" class="btn btn-danger btn-sm deleteVehiculo"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->addColumn('action3', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id .
                        '" data-original-title="Ver" class="btn btn-warning btn-sm viewVehiculo"><i class="fa fa-eye"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action1', 'action2', 'action3'])
                ->make(true);
        }
        return view('gestion.vehiculo.index');
    }

    /**
     * Almacena un nuevo vehículo.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validación: asegúrate de incluir más reglas según tu necesidad
            $request->validate([
                'placa'   => 'required',
                'TIV_Id'  => 'required|exists:tipos_vehiculos,id',
                // CLI_Id es opcional
                'color'   => 'required'
            ]);

            $vehiculo = new Vehiculo();

            /* 
             * Si en el formulario de registro la placa se ingresa como array (6 inputs separados para cada dígito/letter)
             * se concatena para formar la placa final. De lo contrario se toma directamente el valor.
             */
            if (is_array($request->placa)) {
                $vehiculo->placa = implode('', $request->placa);
            } else {
                $vehiculo->placa = strtoupper($request->placa);
            }

            $vehiculo->TIV_Id = $request->TIV_Id;
            $vehiculo->CLI_Id = $request->CLI_Id; // opcional
            $vehiculo->color   = $request->color;
            $vehiculo->marca   = $request->marca;
            $vehiculo->modelo  = $request->modelo;
            $vehiculo->save();

            DB::commit();
            return response()->json([
                'success' => 'Vehículo registrado exitosamente!',
                'data' => $vehiculo,
                'id' => $vehiculo->id
            ]);
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al registrar el vehículo', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Muestra la información de un vehículo.
     */
    public function show($id)
    {
        $vehiculo = Vehiculo::find($id);
        return response()->json(['data' => $vehiculo]);
    }

    /**
     * Muestra los datos de un vehículo para edición.
     */
    public function edit($id)
    {
        $vehiculo = Vehiculo::find($id);
        return response()->json(['data' => $vehiculo]);
    }

    /**
     * Actualiza la información de un vehículo.
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Validación básica
            $request->validate([
                'placa'   => 'required',
                'TIV_Id'  => 'required|exists:tipos_vehiculos,id',
                'color'   => 'required',
                'marca'   => 'required',
                'modelo'  => 'required',
            ]);

            $vehiculo = Vehiculo::find($id);

            if (is_array($request->placa)) {
                $vehiculo->placa = implode('', $request->placa);
            } else {
                $vehiculo->placa = $request->placa;
            }

            $vehiculo->TIV_Id = $request->TIV_Id;
            $vehiculo->CLI_Id = $request->CLI_Id;
            $vehiculo->color   = $request->color;
            $vehiculo->marca   = $request->marca;
            $vehiculo->modelo  = $request->modelo;
            $vehiculo->update();

            DB::commit();
            return response()->json(['success' => 'Vehículo actualizado exitosamente', 'data' => $vehiculo]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al actualizar el vehículo', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Elimina un vehículo.
     */
    public function destroy($id)
    {
        try {
            $vehiculo = Vehiculo::find($id);
            $vehiculo->delete();

            return response()->json(['success' => 'Vehículo eliminado exitosamente']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar el vehículo', 'message' => $e->getMessage()], 500);
        }
    }

    public function buscar(Request $request)
    {
        // Validar que se reciba el parámetro 'placa'
        $request->validate([
            'placa' => 'required|string|size:6'
        ]);

        $placa = $request->get('placa');

        // Buscar el vehículo por placa (ajusta según el nombre del campo en tu BD)
        $vehiculo = Vehiculo::where('placa', $placa)->first();

        if ($vehiculo) {
            // Retornar JSON con la información necesaria
            return response()->json([
                'exists' => true,
                'data' => [
                    'color'  => $vehiculo->color,
                    'marca'  => $vehiculo->marca,
                    'modelo' => $vehiculo->modelo,
                    'tipovehiculo' => $vehiculo->TIV_Id,
                    'id' => $vehiculo->id
                ]
            ]);
        } else {
            // En caso de que no se encuentre, se retorna exists false
            return response()->json(['exists' => false]);
        }
    }

    public function filterEspacio(Request $request)
    {
        $cocId = $request->input('cocId');
        $tipos = EspacioParqueo::select('espacios_parqueo.id', 'espacios_parqueo.codigo', 'tipos_vehiculos.nombre as tipo_vehiculo', 'espacios_parqueo.state as estado', 'tipos_vehiculos.imagen')
                ->leftjoin('tipos_vehiculos', 'espacios_parqueo.TIV_Id', '=', 'tipos_vehiculos.id')
                ->where('espacios_parqueo.COC_Id', $cocId)
                ->get();

        // Retornar la lista en JSON
        return response()->json($tipos);
    }

    public function obtenerPrecioPorPlaca(Request $request)
    {
        $placa = $request->input('placa');
        $cocId = $request->input('cocId');

        // Obtener el vehículo por placa
        $vehiculo = DB::table('vehiculos')->where('placa', $placa)->first();

        if ($vehiculo) {
            // Buscar tarifa según COC_Id
            $tarifa = DB::table('tarifas')
                ->where('TIV_Id', $vehiculo->TIV_Id)
                ->first();

            if ($tarifa) {
                return response()->json(['precio_hora' => $tarifa->precio_hora]);
            }
        }

        return response()->json(['precio_hora' => null]);
    }
}
