<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EspacioParqueo;
use App\Models\Cliente;
use App\Models\Marcacion;
use App\Models\Vehiculo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Muestra una lista de clientes.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Se obtiene la data de la tabla clientes.
            $data = DB::table('clientes')->get();

            return datatables()::of($data)
                ->addIndexColumn()
                ->addColumn('action1', function ($row) {
                    $btn = '<a data-toggle="tooltip" data-identificador="' . $row->id .
                        '" data-original-title="Editar" class="editCliente btn btn-info btn-sm editCliente"><i class="fa fa-edit"></i></a>';
                    return $btn;
                })
                ->addColumn('action2', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id .
                        '" data-original-title="Eliminar" class="btn btn-danger btn-sm deleteCliente"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->addColumn('action3', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id .
                        '" data-original-title="Ver" class="btn btn-warning btn-sm viewCliente"><i class="fa fa-eye"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action1', 'action2', 'action3'])
                ->make(true);
        }
        return view('gestion.cliente.index');
    }

    /**
     * Almacena un nuevo cliente.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validación: asegúrate de incluir más reglas según tu necesidad
            $request->validate([
                'documento'   => 'required',
                'nombre'   => 'required'
            ]);
            
            $cliente = new Cliente();
            $cliente->documento = $request->documento;
            $cliente->nombre = $request->nombre;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->save();
            
            $vehiculo = Vehiculo::find($request->VEH_Id);
            $vehiculo->CLI_Id = $cliente->id;
            $vehiculo->update();
            
            $espacioparqueo = EspacioParqueo::find($request->ESP_Id);
            $espacioparqueo->state = 'ocupado';
            $espacioparqueo->update();



            DB::commit();
            return response()->json([
                'success' => 'Cliente registrado exitosamente!',
                'data' => $cliente,
                'id' => $cliente->id
            ]);
            
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al registrar el cliente', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Muestra la información de un cliente.
     */
    public function show($id)
    {
        $cliente = Cliente::find($id);
        return response()->json(['data' => $cliente]);
    }

    /**
     * Muestra los datos de un cliente para edición.
     */
    public function edit($id)
    {
        $cliente = Cliente::find($id);
        return response()->json(['data' => $cliente]);
    }

    /**
     * Actualiza la información de un cliente.
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Validación básica
            $request->validate([
                'placa'   => 'required',
                'TIV_Id'  => 'required|exists:tipos_clientes,id',
                'color'   => 'required',
                'marca'   => 'required',
                'modelo'  => 'required',
            ]);

            $cliente = Cliente::find($id);

            if (is_array($request->placa)) {
                $cliente->placa = implode('', $request->placa);
            } else {
                $cliente->placa = $request->placa;
            }

            $cliente->TIV_Id = $request->TIV_Id;
            $cliente->CLI_Id = $request->CLI_Id;
            $cliente->color   = $request->color;
            $cliente->marca   = $request->marca;
            $cliente->modelo  = $request->modelo;
            $cliente->update();

            DB::commit();
            return response()->json(['success' => 'Cliente actualizado exitosamente', 'data' => $cliente]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al actualizar el cliente', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Elimina un cliente.
     */
    public function destroy($id)
    {
        try {
            $cliente = Cliente::find($id);
            $cliente->delete();

            return response()->json(['success' => 'Cliente eliminado exitosamente']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al eliminar el cliente', 'message' => $e->getMessage()], 500);
        }
    }

    public function buscar(Request $request)
    {
        // Validar que se reciba el parámetro 'placa'
        $request->validate([
            'placa' => 'required|string|size:6'
        ]);

        $placa = $request->get('placa');

        // Buscar el cliente por placa (ajusta según el nombre del campo en tu BD)
        $cliente = Cliente::where('placa', $placa)->first();

        if ($cliente) {
            // Retornar JSON con la información necesaria
            return response()->json([
                'exists' => true,
                'data' => [
                    'color'  => $cliente->color,
                    'marca'  => $cliente->marca,
                    'modelo' => $cliente->modelo,
                    'tipocliente' => $cliente->TIV_Id,
                    'id' => $cliente->id
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
        $tipos = EspacioParqueo::select('espacios_parqueo.id', 'espacios_parqueo.codigo', 'tipos_clientes.nombre as tipo_cliente', 'espacios_parqueo.state as estado', 'tipos_clientes.imagen')
                ->leftjoin('tipos_clientes', 'espacios_parqueo.TIV_Id', '=', 'tipos_clientes.id')
                ->where('espacios_parqueo.COC_Id', $cocId)
                ->get();

        // Retornar la lista en JSON
        return response()->json($tipos);
    }

    public function obtenerPrecioPorPlaca(Request $request)
    {
        $placa = $request->input('placa');
        $cocId = $request->input('cocId');

        // Obtener el cliente por placa
        $cliente = DB::table('clientes')->where('placa', $placa)->first();

        if ($cliente) {
            // Buscar tarifa según COC_Id
            $tarifa = DB::table('tarifas')
                ->where('TIV_Id', $cliente->TIV_Id)
                ->first();

            if ($tarifa) {
                return response()->json(['precio_hora' => $tarifa->precio_hora]);
            }
        }

        return response()->json(['precio_hora' => null]);
    }
}
