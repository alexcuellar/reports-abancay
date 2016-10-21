<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use PDF;
use Symfony\Component\Process\Process;
class ReportPdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

     public function index(Request $request)
    {
        DB::disableQueryLog();
        /*$taxes = DB::table('taxes')->join('persons', 'taxes.propietario_id', '=', 'persons.persona_municipio_id')
                    ->where('ano_aplicacion', '=', date('Y', strtotime("{$request->fecha}")))
                    ->where('cuota', '=', date('m', strtotime("{$request->fecha}")))
                    ->where('importe_cuota', '>', '0')
                    ->whereIn('derecho_emision_grupo_id', [3,4,5])
                    ->orderBy('APELLIDO_PATERNO', 'asc')
                    ->orderBy('APELLIDO_MATERNO', 'asc')
                    ->orderBy('NOMBRE', 'asc')
                    ->orderBy('cuota', 'desc')
                    ->get();*/
        $taxes = DB::table('taxes')->join('persons', 'taxes.propietario_id', '=', 'persons.persona_municipio_id')
                    ->where('ano_aplicacion', '=', date('Y', strtotime("{$request->fecha}")))
                    ->where('cuota', '<=', date('m', strtotime("{$request->fecha}")))
                    ->where('importe_cuota', '>', '0')
                    ->where('direccion', 'like', '%' . $request->direccion . '%')
                    ->whereIn('derecho_emision_grupo_id', [3,4,5])
                    ->whereNotIn('cuenta_corriente_resumen_id', function($query)
                        {
                            $query->select(DB::raw('cuenta_corriente_resumen_id'))
                                  ->from('movements')
                                  ->whereRaw('movements.cuenta_corriente_resumen_id = taxes.cuenta_corriente_resumen_id');
                        })
                    ->orderBy('APELLIDO_PATERNO', 'asc')
                    ->orderBy('APELLIDO_MATERNO', 'asc')
                    ->orderBy('NOMBRE', 'asc')
                    ->orderBy('cuota', 'desc')
                    ->get();
        $charges= DB::table('charges')->where('ano_aplicacion', '=', date('Y', strtotime("{$request->fecha}")))
                    ->orderBy('MES_APLICACION', 'asc')
                    ->get();
        //dd(collect($taxes)->groupBy('predio_expediente_id'));
        //return view('report.report', ['taxes' => collect($taxes)->groupBy('predio_expediente_id'), 'fecha'=>$request->fecha, 'charges'=>$charges]);
        return PDF::loadView('report.report', ['taxes' => collect($taxes)->groupBy('predio_expediente_id'), 'fecha'=>$request->fecha, 'charges'=>$charges])->setPaper('a5')->setOption('margin-top', 37)->setOption('margin-bottom', 0)->setOption('margin-left', 10)->setOption('margin-right', 10)->setOrientation('portrait')->download('recibos-fecha'.'-'.$request->fecha.'.pdf');
    }
	public function get_person($person_id){
		$person = DB::table('PERSONS')
			->where('PERSONA_MUNICIPIO_ID', '=', $person_id)
			->get();
		return $person; 
	}
	
}
