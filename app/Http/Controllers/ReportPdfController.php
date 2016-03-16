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
        $taxes = DB::table('taxes')->join('persons', 'taxes.propietario_id', '=', 'persons.persona_municipio_id')
                    ->where('ano_aplicacion', '=', date('Y', strtotime("{$request->fecha}")))
                    ->where('cuota', '<=', date('m', strtotime("{$request->fecha}")))
                    ->where('importe_cuota', '>', '0')
                    ->whereIn('derecho_emision_grupo_id', [3,4,5])
                    ->orderBy('APELLIDO_PATERNO', 'asc')
                    ->orderBy('APELLIDO_MATERNO', 'asc')
                    ->orderBy('NOMBRE', 'asc')
                    ->orderBy('cuota', 'desc')
                    ->get();
        return PDF::loadView('report.report', ['taxes' => collect($taxes)->groupBy('predio_expediente_id')])->setPaper('a5')->setOrientation('landscape')->download('recibos-fecha'.'-'.$request->fecha.'.pdf');
    }
	public function get_person($person_id){
		$person = DB::table('PERSONS')
			->where('PERSONA_MUNICIPIO_ID', '=', $person_id)
			->get();
		return $person; 
	}
	
}
