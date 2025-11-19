<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cliente;
use App\Models\Cochera;
use App\Models\EspacioParqueo;
use App\Models\Marcacion;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Illuminate\Support\Facades\Http;

class MarcacionController extends Controller
{
    /**
     * Muestra una lista de marcaciones.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Si se requiere incluir relaciones, puedes usar with() en lugar de DB::table
            $data = DB::table('marcaciones')
                ->leftjoin('vehiculos', 'marcaciones.VEH_Id', '=', 'vehiculos.id')
                ->leftjoin('espacios_parqueo', 'marcaciones.ESP_Id', '=', 'espacios_parqueo.id')
                ->select(
                    'marcaciones.*',
                    'vehiculos.placa as vehiculo_placa',
                    'espacios_parqueo.codigo as espacio_nombre',
                    'espacios_parqueo.state'
                )
                ->get();

                return datatables()::of($data)
                ->addIndexColumn()
        
                ->addColumn('action1', function ($row) {
                    // Si la marcación está "ocupado" mostramos botón para terminar
                    if ($row->state === 'ocupado') {
                        return
                            '<a
                                href="javascript:void(0)"
                                data-id="' . $row->id . '"
                                title="Terminar marcación"
                                class="getDataMarcacionTerminar btn btn-success btn-sm"
                            >
                                <i class="fa fa-check"></i>
                            </a>';
                    }
                
                    // En cualquier otro caso (por ejemplo "activo") mostramos el botón de asignar/editar
                    if ($row->estado != 'finalizado') {
                    return
                        '<a
                            href="javascript:void(0)"
                            data-id="' . $row->id . '"
                            title="Asignar"
                            class="editMarcacion btn btn-warning btn-sm"
                        >
                            <i class="fa fa-edit"></i>
                        </a>';
                    }

                    return
        '<button
            class="btn btn-warning btn-sm"
            disabled
            title="Asignar (no disponible en estado finalizado)"
        >
            <i class="fa fa-edit"></i>
        </button>';

                })                
        
                // “Cancelar” (reemplaza eliminar)
                ->addColumn('action2', function ($row) {
                    if ($row->estado === 'cancelado') {
                        // Si ya está cancelado, botón deshabilitado
                        return
                            '<button
                                class="btn btn-danger btn-sm"
                                disabled
                                title="Ya cancelado"
                            ><i class="fa fa-times-circle"></i></button>';
                    }
        
                    // Si está activo u otro estado, botón activo
                    return
                        '<a
                            href="javascript:void(0)"
                            data-id="' . $row->id . '"
                            title="Cancelar"
                            class="deleteMarcacion btn btn-danger btn-sm"
                        ><i class="fa fa-times-circle"></i></a>';
                })
        
                ->rawColumns(['action1', 'action2'])
                ->make(true);
        }

        $idusu = Auth::user()->id;
        // Para peticiones normales, se pasan los datos necesarios a la vista.
        $vehiculos = Vehiculo::all();
        $espacios = EspacioParqueo::all();
        $marcaciones = Marcacion::all();
        $tiposVehiculos = TipoVehiculo::all();
        $clientes = Cliente::all();
        //$cocheras = Cochera::all();
        $cocheras = DB::table('cocheras')
            ->join('usuario_cocheras', 'cocheras.id', '=', 'usuario_cocheras.COC_Id')
            ->where('usuario_cocheras.USU_Id', Auth::id())
            ->select('cocheras.*')
            ->get();

        // Si solo hay una, la guardamos para marcarla automáticamente
        $selectedCoc = $cocheras->count() === 1
            ? $cocheras->first()->id
            : null;

        return view('gestion.marcacion.index', compact('vehiculos', 'espacios', 'marcaciones', 'tiposVehiculos', 'clientes', 'cocheras', 'selectedCoc'));
    }

    public function create()
    {
        $idempresa = Auth::user()->EMP_Id;
        // Aquí puedes cargar los datos necesarios para el formulario de creación
        $vehiculos = Vehiculo::all();
        $espacios_parqueo = DB::table('espacios_parqueo as ep')
            ->leftjoin('tipos_vehiculos as tv', 'tv.id', '=', 'ep.TIV_Id')
            ->join('cocheras as c', 'c.id', '=', 'ep.COC_Id')
            ->select('ep.*', 'tv.nombre as TipoVehiculo', 'tv.imagen as Imagen', 'c.nombre as Cochera')
            ->where('ep.estado', 1)
            ->get();
        $marcaciones = DB::table('marcaciones as m')
            ->leftjoin('vehiculos as v', 'v.id', '=', 'm.VEH_Id')
            ->select('m.*', 'v.placa as Placa')
            ->where('fecha_salida', null)
            ->get();

        $tiposVehiculos = TipoVehiculo::all();
        $clientes = Cliente::all();
        $cocheras = Cochera::all();
        $ahora = Carbon::now();
        foreach ($espacios_parqueo as $espacio) {
            $espacio->identificador = strtoupper($espacio->id);
            foreach ($marcaciones as $marcacion) {
                if ($espacio->id == $marcacion->ESP_Id) {
                    $espacio->identificador = strtoupper($marcacion->id);
                    $espacio->placa = strtoupper($marcacion->Placa);
                    $espacio->fecha_entrada = $marcacion->fecha_entrada;
                    $espacio->hora_entrada = Carbon::parse($marcacion->fecha_entrada)->format('H:i');
                    $espacio->tiempo = Carbon::parse($marcacion->fecha_entrada)->format('H:i');
                    $espacio->tiempo_total = $this->tiempoTranscurrido($marcacion->fecha_entrada);
                }
            }
        }

        if (Auth::user()->esAdministrador()) {
            $empresas = DB::table('empresas')->where('estado', 1)->get();
        } else {
            $empresas = DB::table('empresas')->where('id', $idempresa)->where('estado', 1)->get();
        }
        $admin = Auth::user()->esAdministrador();

        return view('gestion.marcacion.create', compact('vehiculos', 'tiposVehiculos', 'clientes', 'cocheras', 'espacios_parqueo', 'empresas', 'admin'));
    }

    public function espaciosAllfindCochera($idcochera)
    {
        $espacios_parqueo = DB::table('espacios_parqueo as ep')
            ->leftjoin('tipos_vehiculos as tv', 'tv.id', '=', 'ep.TIV_Id')
            ->join('cocheras as c', 'c.id', '=', 'ep.COC_Id')
            ->select('ep.*', 'tv.nombre as TipoVehiculo', 'tv.imagen as Imagen', 'c.nombre as Cochera')
            ->where('ep.estado', 1)
            ->where('ep.COC_Id', $idcochera)
            ->get();
        $marcaciones = DB::table('marcaciones as m')
            ->leftjoin('vehiculos as v', 'v.id', '=', 'm.VEH_Id')
            ->select('m.*', 'v.placa as Placa')
            ->where('fecha_salida', null)
            ->get();

        foreach ($espacios_parqueo as $espacio) {
            $espacio->identificador = strtoupper($espacio->id);
            foreach ($marcaciones as $marcacion) {
                if ($espacio->id == $marcacion->ESP_Id) {
                    $espacio->identificador = strtoupper($marcacion->id);
                    $espacio->placa = strtoupper($marcacion->Placa);
                    $espacio->fecha_entrada = $marcacion->fecha_entrada;
                    $espacio->hora_entrada = Carbon::parse($marcacion->fecha_entrada)->format('H:i');
                    $espacio->tiempo = Carbon::parse($marcacion->fecha_entrada)->format('H:i');
                    $espacio->tiempo_total = $this->tiempoTranscurrido($marcacion->fecha_entrada);
                }
            }
        }

        $listIdEspacios = DB::table('espacios_parqueo')
            ->where('COC_Id',$idcochera)
            ->where('estado',1)
            ->whereNot('state','mantenimiento')
            ->pluck('id');

        $montototaldiario = DB::table('marcaciones')
            ->whereIn('ESP_Id',$listIdEspacios)
            ->whereDate('fecha_entrada', Carbon::today())
            ->sum('monto_total');

        $numeroIngresos = DB::table('marcaciones')
            ->whereIn('ESP_Id',$listIdEspacios)
            ->whereDate('fecha_entrada', Carbon::today())
            ->count();

        $numeroEstacionados = DB::table('marcaciones')
            ->whereIn('ESP_Id',$listIdEspacios)
            ->whereNull('fecha_salida')
            ->count();
        
        $numeroEspaciosLibres = DB::table('espacios_parqueo')
            ->where('COC_Id',$idcochera)
            ->where('estado',1)
            ->where('state','disponible')
            ->count();

        return response()->json([
            'data' => $espacios_parqueo,
            'montodiario' => $montototaldiario,
            'numeroIngresos' => $numeroIngresos,
            'numeroEstacionados' => $numeroEstacionados,
            'numeroEspaciosLibres' => $numeroEspaciosLibres
        ]);
    }

    function tiempoTranscurrido($fechaInicio)
    {
        $inicio = Carbon::parse($fechaInicio);
        $ahora = Carbon::now();

        // Calcular la diferencia en minutos, horas y días
        $diffInMinutes = round($inicio->diffInMinutes($ahora, false), 2); // false para que se maneje correctamente si el tiempo es negativo
        $diffInHours = round($inicio->diffInHours($ahora, false), 2);
        $diffInDays = round($inicio->diffInDays($ahora, false), 2);

        // Si son menos de 60 minutos, devolver los minutos
        if ($diffInMinutes < 60) {
            return "$diffInMinutes min";
        }

        // Si son menos de 24 horas, devolver horas y minutos
        elseif ($diffInHours < 24) {
            $horas = floor($diffInMinutes / 60);
            $minutos = $diffInMinutes % 60;
            return sprintf("%d:%02d hrs", $horas, $minutos);
        }

        // Si han pasado más de 24 horas, calcular los días, horas y minutos
        else {
            $dias = $diffInDays;
            // Calculamos las horas restantes luego de contar los días completos
            $restoHoras = $inicio->addDays($dias)->diffInHours($ahora, false);
            // Calculamos los minutos restantes después de contar las horas
            $restoMinutos = $inicio->addDays($dias)->addHours($restoHoras)->diffInMinutes($ahora, false) % 60;

            return sprintf("%d día%s %d:%02d hr", $dias, $dias > 1 ? 's' : '', $restoHoras, $restoMinutos);
        }
    }

    function tiempoTranscurridoEntero($fechaInicio, $unidad = 'hora')
    {
        $inicio = Carbon::parse($fechaInicio);
        $ahora = Carbon::now();

        switch (strtolower($unidad)) {
            case 'minuto':
                return (int) ceil($inicio->diffInMinutes($ahora, false));
            case 'hora':
                return (int) ceil($inicio->diffInHours($ahora, false));
            case 'dia':
                return (int) ceil($inicio->diffInDays($ahora, false));
            case 'semana':
                return (int) ceil($inicio->diffInWeeks($ahora, false));
            case 'mes':
                return (int) ceil($inicio->diffInMonths($ahora, false));
            default:
                throw new InvalidArgumentException("Unidad no válida: $unidad");
        }
    }

    /**
     * Almacena una nueva marcación.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            // Opcional: Validación básica de campos
            $request->validate([
                'VEH_Id'       => 'required|exists:vehiculos,id'
            ]);

            $marcacion = new Marcacion();
            $marcacion->ESP_Id = $request->ESP_Id;
            $marcacion->VEH_Id = $request->VEH_Id;
            $marcacion->USU_Id = Auth::id();
            $marcacion->fecha_entrada = Carbon::now();
            $marcacion->tipo_marcacion = $request->tarifa;
            $marcacion->estado = "activo";
            $marcacion->save();

            $vehiculo = Vehiculo::find($request->VEH_Id);
            $estadoespacio = "pendiente";
            if ($vehiculo->CLI_Id) {
                $estadoespacio = "ocupado";
            }

            $espacios_parqueo = EspacioParqueo::find($request->ESP_Id);
            $espacios_parqueo->state = $estadoespacio;
            $espacios_parqueo->update();

            DB::commit();
            return response()->json(['success' => 'Marcación registrada exitosamente!', 'data' => $marcacion]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al registrar la marcación', 'message' => $e->getMessage()], 500);
        }
    }

    public function emitirBoleta($id)
    {
        // 1) Obtener la marcación y relaciones
        $marcacion = Marcacion::with(['cliente', 'detalles'])->findOrFail($id);

        // 2) Armar el payload
        $payload = [
            'serie'        => $marcacion->serie,
            'correlativo'  => $marcacion->correlativo,
            'cliente'      => [
                'num_doc'      => $marcacion->cliente->ruc,
                'razon_social' => $marcacion->cliente->razon_social,
            ],
            'detalle'      => $marcacion->detalles->map(function($item) {
                return [
                    'cod_producto'    => $item->producto_codigo,
                    'descripcion'     => $item->producto_descripcion,
                    'unidad'          => $item->unidad_medida,
                    'cantidad'        => $item->cantidad,
                    'valor_unitario'  => $item->valor_unitario,
                    'valor_venta'     => $item->cantidad * $item->valor_unitario,
                    'base_igv'        => $item->cantidad * $item->valor_unitario,
                    'porcentaje_igv'  => $item->porcentaje_igv,
                    'igv'             => ($item->cantidad * $item->valor_unitario) * ($item->porcentaje_igv / 100),
                    'tipo_afectacion' => $item->tipo_afectacion,
                    'precio_unitario' => $item->valor_unitario * (1 + $item->porcentaje_igv / 100),
                ];
            })->toArray(),
            'moneda'       => $marcacion->moneda,
            'total'        => $marcacion->detalles->sum(function($item) {
                                   return ($item->cantidad * $item->valor_unitario) * (1 + $item->porcentaje_igv / 100);
                               }),
            'forma_pago'   => $marcacion->forma_pago,
        ];

        // 3) Llamar a la API externa
        $response = Http::withToken('5|ubGgJ2KY04A270IcHyQz2so6hiQzUkiGWAXVvfMDN64bc6228')
            ->post('https://agenciamerlo.online/api/emitir', $payload);

        if ($response->successful()) {
            // Si la API devuelve PDF o URL, podrías retornarla aquí
            // por ejemplo: $urlBoleta = $response->json('data.url');
            return response()->json([
                'success' => 'Boleta emitida correctamente',
                // 'url'     => $urlBoleta,
            ]);
        }

        return response()->json([
            'error'   => 'Error al emitir boleta',
            'detalle' => $response->body(),
        ], $response->status());
    }

    public function storecerrar(Request $request)
    {
        try {
            DB::beginTransaction();
            // dd($request->all());
            $marcacion = Marcacion::find($request->MAR_Id);
            $marcacion->fecha_salida = Carbon::now();
            $marcacion->monto_total = $request->monto_total;
            $marcacion->estado = "finalizado";
            $marcacion->update();

            $estadoespacio = "disponible";

            $espacios_parqueo = EspacioParqueo::find($marcacion->ESP_Id);
            $espacios_parqueo->state = $estadoespacio;
            $espacios_parqueo->update();

            DB::commit();
            return response()->json(['success' => 'Marcación Finalizada exitosamente!', 'data' => $marcacion]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al finalizar la marcación', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Muestra la información de una marcación en específico.
     */
    public function show($id)
    {
        // $marcacion = Marcacion::find($id);
        $marcacion = DB::table('marcaciones as m')
            ->leftjoin('espacios_parqueo as ep', 'ep.id', '=', 'm.ESP_Id')
            ->leftjoin('vehiculos as v', 'v.id', '=', 'm.VEH_Id')
            ->leftjoin('tipos_vehiculos as tv', 'tv.id', '=', 'v.TIV_Id')
            ->leftjoin('clientes as c', 'c.id', '=', 'v.CLI_Id')
            ->select('m.*', 'v.*', 'c.*', 'ep.id as IdEspacio', 'ep.id as IdEspacio', 'ep.codigo as CodigoEspacio', 'ep.COC_Id', 'tv.*', 'c.nombre as NombreCliente')
            ->where('m.id', $id)
            ->first();

        $tarifa = DB::table('tarifas')
            ->where('TIV_Id', $marcacion->TIV_Id)
            ->where('COC_Id', $marcacion->COC_Id)
            ->where('estado', 1)
            ->first();

        if ($marcacion->tipo_marcacion == 'hora') {
            $marcacion->tarifa = $tarifa->precio_hora;
        } else if ($marcacion->tipo_marcacion == 'dia') {
            $marcacion->tarifa = $tarifa->precio_dia;
        } else if ($marcacion->tipo_marcacion == 'semana') {
            $marcacion->tarifa = $tarifa->precio_semana;
        } else if ($marcacion->tipo_marcacion == 'mes') {
            $marcacion->tarifa = $tarifa->precio_mes;
        }
        $marcacion->fechaentrada = Carbon::parse($marcacion->fecha_entrada)->format('Y-m-d');
        $marcacion->horaentrada = Carbon::parse($marcacion->fecha_entrada)->format('H:i');
        $marcacion->tiempo = $this->tiempoTranscurrido($marcacion->fecha_entrada);
        $marcacion->total = $this->tiempoTranscurridoEntero($marcacion->fecha_entrada, $marcacion->tipo_marcacion) * $marcacion->tarifa;

        return response()->json(['data' => $marcacion]);
    }

    public function show1($id)
    {
        $marcacion = DB::table('marcaciones as m')
            ->leftjoin('espacios_parqueo as ep', 'ep.id', '=', 'm.ESP_Id')
            ->leftjoin('vehiculos as v', 'v.id', '=', 'm.VEH_Id')
            ->leftjoin('tipos_vehiculos as tv', 'tv.id', '=', 'v.TIV_Id')
            ->select('m.*', 'v.*', 'ep.id as IdEspacio', 'ep.id as IdEspacio', 'ep.codigo as CodigoEspacio', 'ep.COC_Id', 'tv.*')
            ->where('m.id', $id)
            ->first();

        $tarifa = DB::table('tarifas')
            ->where('TIV_Id', $marcacion->TIV_Id)
            ->where('COC_Id', $marcacion->COC_Id)
            ->where('estado', 1)
            ->first();

        if ($marcacion->tipo_marcacion == 'hora') {
            $marcacion->tarifa = $tarifa->precio_hora;
        } else if ($marcacion->tipo_marcacion == 'dia') {
            $marcacion->tarifa = $tarifa->precio_dia;
        } else if ($marcacion->tipo_marcacion == 'semana') {
            $marcacion->tarifa = $tarifa->precio_semana;
        } else if ($marcacion->tipo_marcacion == 'mes') {
            $marcacion->tarifa = $tarifa->precio_mes;
        }
        $marcacion->fechaentrada = Carbon::parse($marcacion->fecha_entrada)->format('Y-m-d');
        $marcacion->horaentrada = Carbon::parse($marcacion->fecha_entrada)->format('H:i');
        $marcacion->tiempo = $this->tiempoTranscurrido($marcacion->fecha_entrada);
        $marcacion->total = $this->tiempoTranscurridoEntero($marcacion->fecha_entrada, $marcacion->tipo_marcacion) * $marcacion->tarifa;

        return response()->json(['data' => $marcacion]);
    }

    /**
     * Muestra los datos de una marcación para ser editada.
     */
    public function edit($id)
    {
        $marcacion = Marcacion::find($id);
        return response()->json(['data' => $marcacion]);
    }

    /**
     * Actualiza la información de una marcación.
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Validar campos si es necesario
            $request->validate([
                'ESP_Id'       => 'required|exists:espacios_parqueo,id',
                'VEH_Id'       => 'required|exists:vehiculos,id',
                'USU_Id'       => 'required|exists:users,id',
                'fecha_entrada' => 'required|date',
                'monto_total'  => 'required|numeric',
                'estado'       => 'required|in:activo,finalizado,cancelado',
            ]);

            $marcacion = Marcacion::find($id);
            $marcacion->ESP_Id = $request->ESP_Id;
            $marcacion->VEH_Id = $request->VEH_Id;
            $marcacion->USU_Id = $request->USU_Id;
            $marcacion->fecha_entrada = $request->fecha_entrada;
            $marcacion->fecha_salida = $request->fecha_salida;
            $marcacion->monto_total = $request->monto_total;
            $marcacion->estado = $request->estado;
            $marcacion->update();

            DB::commit();
            return response()->json(['success' => 'Marcación actualizada exitosamente', 'data' => $marcacion]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al actualizar la marcación', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Marca una marcación como eliminada o la elimina físicamente.
     */
    public function destroy($id)
    {
        $marcacion = Marcacion::find($id);
        // Por ejemplo, podríamos cambiar su estado a "cancelado" para indicar eliminación lógica
        $marcacion->estado = 'cancelado';
        $marcacion->update();

        return response()->json(['success' => 'Marcación eliminada exitosamente']);
    }
}
