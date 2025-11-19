<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
			$data = DB::table('empresas')->where('estado',1)->get();
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
               
                ->rawColumns(['action1','action2','action3'])
                ->make(true);
        }
        return view('seguridad.empresa.index');
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

            $query=Empresa::where('nombre','=',$request->get('nombre'))->get();
            if($query->count()!=0) //si lo encuentra, osea si no esta vacia
            {
                
                return response()->json(['error' => 'Empresa ya registrado'], 401);                   
            }
            else{
                $empresa=new Empresa();
                $empresa->nombre=$request->nombre;
                $empresa->ruc=$request->ruc;
                $empresa->direccion=$request->direccion;
                $empresa->telefono=$request->telefono;
                $empresa->email=$request->email;
                $empresa->save();

                $path = 'storage/archivos/empresa/';
                $file = $request->file('file');
                if($file){
                    $extension = $file->getClientOriginalExtension();
                    $fileName =  $empresa->id . '.' . $extension;
                    $upload = $file->move($path, $fileName);
                    
                    $updateempresa = DB::table('empresas')
                    ->where('id', $empresa->id)
                    ->update(['logo_url' => $fileName]);
                }
                   
            }

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }        
        return response()->json(['success' => 'Empresa Registrado Exitosamente!',compact('empresa')]); 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $empresa = Empresa::find($id);
        $imagen="";
        if($empresa->logo_url){
            $imagen = 'storage/archivos/empresa/'.$empresa->logo_url.'?' . uniqid();
        }
        return response()->json(['data' => $empresa,'imagen'=> $imagen]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $empresa = Empresa::find($id);
        return response()->json(['data' => $empresa]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $empresa = Empresa::find($id);
            $empresa->nombre=$request->nombre;
            $empresa->ruc=$request->ruc;
            $empresa->direccion=$request->direccion;
            $empresa->telefono=$request->telefono;
            $empresa->email=$request->email;
            $empresa->update();

            $path = 'storage/archivos/empresa/';
            $file = $request->file('file');
            if($file){
                $extension = $file->getClientOriginalExtension();
                $fileName =  $empresa->id . '.' . $extension;
                $upload = $file->move($path, $fileName);
                
                $empresa = DB::table('empresas')
                ->where('id', $empresa->id)
                ->update(['logo_url' => $fileName]);
            }

            DB::commit();
        } catch (Exception $e)
        {
            DB::rollback();
        }

        return response()->json(['success' => 'Empresa Editado Exitosamente.',compact('empresa')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $empresa = Empresa::find($id);
        $empresa->estado = 0;
		$empresa->update();

        return response()->json(['success' => 'Empresa Eliminado Exitosamente.']);
    }
}
