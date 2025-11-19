<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cochera;
use App\Models\Empresa;
use App\Models\TipoVehiculo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    public function index()
    {
        $idempresa = Auth::user()->EMP_Id; 
        $admin = Auth::user()->esAdministrador();       
        $tiposVehiculos = TipoVehiculo::all();

        if(Auth::user()->esAdministrador()){
            $empresas = DB::table('empresas')->where('estado',1)->get();

        }else{  
            $empresas = DB::table('empresas')->where('id',$idempresa)->where('estado',1)->get();
        }

        $cochera = Cochera::where('EMP_Id',$empresas[0]->id)->orderBy('id','asc')->first();
        $listIdEspacios = DB::table('espacios_parqueo')
            ->where('COC_Id',$cochera->id)
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
            ->where('COC_Id',$cochera->id)
            ->where('estado',1)
            ->where('state','disponible')
            ->count();

        
        return view('menu.home',compact( 'tiposVehiculos','empresas','admin','montototaldiario','numeroIngresos','numeroEstacionados'));
    }
    public function inicio()
    {
        return view('inicio');
    }

    
    public function ticketentrada($id)
    {
        $marcacion = DB::table('marcaciones as m')
        ->leftjoin('espacios_parqueo as ep', 'ep.id', '=', 'm.ESP_Id')
        ->leftjoin('vehiculos as v', 'v.id', '=', 'm.VEH_Id')
        ->leftjoin('tipos_vehiculos as tv', 'tv.id', '=', 'v.TIV_Id')
        ->leftjoin('clientes as c', 'c.id', '=', 'v.CLI_Id')
        ->select('m.*', 'v.*', 'c.*','ep.id as IdEspacio','ep.id as IdEspacio','ep.codigo as CodigoEspacio','ep.COC_Id','tv.*','c.nombre as NombreCliente')
        ->where('m.id', $id)
        ->first();
        
        $tarifa = DB::table('tarifas')
            ->where('TIV_Id', $marcacion->TIV_Id)
            ->where('COC_Id', $marcacion->COC_Id)
            ->where('estado', 1)
            ->first();
            
        if($marcacion->tipo_marcacion == 'hora'){
            $marcacion->tarifa = $tarifa->precio_hora;
        }else if($marcacion->tipo_marcacion == 'dia'){
            $marcacion->tarifa = $tarifa->precio_dia;
        }else if($marcacion->tipo_marcacion == 'semana'){
            $marcacion->tarifa = $tarifa->precio_semana;
        }else if($marcacion->tipo_marcacion == 'mes'){
            $marcacion->tarifa = $tarifa->precio_mes;
        }
        if($marcacion->fecha_salida !== null){
            $marcacion->fechasalida = Carbon::parse($marcacion->fecha_salida)->format('Y-m-d');
            $marcacion->horasalida = Carbon::parse($marcacion->fecha_salida)->format('h:i A');
        }
        $marcacion->tipomoneda = $tarifa->moneda;
        $marcacion->fechaentrada = Carbon::parse($marcacion->fecha_entrada)->format('Y-m-d');
        $marcacion->horaentrada = Carbon::parse($marcacion->fecha_entrada)->format('h:i A');
        $marcacion->tiempo = $this->tiempoTranscurrido($marcacion->fecha_entrada);
        $marcacion->total = $this->tiempoTranscurridoEntero($marcacion->fecha_entrada, $marcacion->tipo_marcacion) * $marcacion->tarifa;
        $cochera = DB::table('cocheras')->where('id',$marcacion->COC_Id)->first();
        $empresa = DB::table('empresas')->where('id',$cochera->EMP_Id)->first();
        $urlCodigo = URL::to('/ticketentrada/PLACA/STATUS'.$id);
        $logoEmpresa="";
        if($empresa->logo_url){
            $logoEmpresa = '/storage/archivos/empresa/'.$empresa->logo_url.'?' . uniqid();
        }
        return view('ticket/ticketEntrada',compact('marcacion','tarifa','empresa','cochera','urlCodigo','logoEmpresa'));
    }

    public function boleta($id)
    {
        $marcacion = DB::table('marcaciones as m')
        ->leftjoin('espacios_parqueo as ep', 'ep.id', '=', 'm.ESP_Id')
        ->leftjoin('vehiculos as v', 'v.id', '=', 'm.VEH_Id')
        ->leftjoin('tipos_vehiculos as tv', 'tv.id', '=', 'v.TIV_Id')
        ->leftjoin('clientes as c', 'c.id', '=', 'v.CLI_Id')
        ->select('m.*', 'v.*', 'c.*','ep.id as IdEspacio','ep.id as IdEspacio','ep.codigo as CodigoEspacio','ep.COC_Id','tv.*','c.nombre as NombreCliente')
        ->where('m.id', $id)
        ->first();
        
        $tarifa = DB::table('tarifas')
            ->where('TIV_Id', $marcacion->TIV_Id)
            ->where('COC_Id', $marcacion->COC_Id)
            ->where('estado', 1)
            ->first();
            
        if($marcacion->tipo_marcacion == 'hora'){
            $marcacion->tarifa = $tarifa->precio_hora;
        }else if($marcacion->tipo_marcacion == 'dia'){
            $marcacion->tarifa = $tarifa->precio_dia;
        }else if($marcacion->tipo_marcacion == 'semana'){
            $marcacion->tarifa = $tarifa->precio_semana;
        }else if($marcacion->tipo_marcacion == 'mes'){
            $marcacion->tarifa = $tarifa->precio_mes;
        }
        $marcacion->fechaentrada = Carbon::parse($marcacion->fecha_entrada)->format('Y-m-d');
        $marcacion->horaentrada = Carbon::parse($marcacion->fecha_entrada)->format('h:i A');
        $marcacion->tiempo = $this->tiempoTranscurrido($marcacion->fecha_entrada);
        $marcacion->total = $this->tiempoTranscurridoEntero($marcacion->fecha_entrada, $marcacion->tipo_marcacion) * $marcacion->tarifa;
        $cochera = DB::table('cocheras')->where('id',$marcacion->COC_Id)->first();
        $empresa = DB::table('empresas')->where('id',$cochera->EMP_Id)->first();
        $urlCodigo = URL::to('/ticketentrada/PLACA/STATUS'.$id);
        $logoEmpresa="";
        if($empresa->logo_url){
            $logoEmpresa = '/storage/archivos/empresa/'.$empresa->logo_url.'?' . uniqid();
        }
        return view('ticket/ticketEntrada',compact('marcacion','tarifa','empresa','cochera','urlCodigo','logoEmpresa'));
    }

    function tiempoTranscurrido($fechaInicio)
    {
        $inicio = Carbon::parse($fechaInicio);
        $ahora = Carbon::now();

        // Calcular la diferencia en minutos, horas y días
        $diffInMinutes = round($inicio->diffInMinutes($ahora, false),2); // false para que se maneje correctamente si el tiempo es negativo
        $diffInHours = round($inicio->diffInHours($ahora, false),2);
        $diffInDays = round($inicio->diffInDays($ahora, false),2);

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

    public function login()
    {
        return view('login');
    }

    public function salir()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
