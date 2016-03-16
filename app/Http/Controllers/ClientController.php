<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Input;

class ClientController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$clients=DB::table('persons')->orderBy('apellido_paterno', 'asc')
									->orderBy('apellido_materno', 'asc')
									->orderBy('nombre', 'asc')
									->take(100)
									->get();
		return view('report.clients', ['clients' => $clients]);
	}
	public function store(Request $request){
		$this->validate($request, ['search' => 'required|max:255']);
		$clients=DB::table('persons')->where('datos_completo', 'like', "%".strtoupper($request->search)."%")
									->orWhere('num_doc', 'like', "%".strtoupper($request->search)."%")
									->orderBy('apellido_paterno', 'asc')
									->orderBy('apellido_materno', 'asc')
									->orderBy('nombre', 'asc')
									->take(100)
									->get();
		return view('report.clients', ['clients' => $clients]);
	}
	public function show(Request $request)
	{
		$num_doc=$request->route()->parameters();
		$taxes = DB::table('taxes')->join('persons', 'taxes.propietario_id', '=', 'persons.persona_municipio_id')
                    ->where('ano_aplicacion', '=', date("Y"))
                    ->where('cuota', '<=', date("n"))
                    ->whereIn('derecho_emision_grupo_id', [3,4,5])
                    ->where('num_doc', '=', $num_doc['id'])
                    ->orderBy('APELLIDO_PATERNO', 'asc')
                    ->orderBy('APELLIDO_MATERNO', 'asc')
                    ->orderBy('NOMBRE', 'asc')
                    ->orderBy('cuota', 'desc')
                    ->take(100)
                    ->get();
		return view('report.client', ['taxes' => collect($taxes)->groupBy('predio_expediente_id')]);
	}
	public function autocomplete(Request $request){
		$term = Input::get('term');
		$results = array();
		$clients=DB::table('persons')->where('datos_completo', 'like', "%".strtoupper($term)."%")
									->orWhere('num_doc', 'like', "%".strtoupper($term)."%")
									->orderBy('apellido_paterno', 'asc')
									->orderBy('apellido_materno', 'asc')
									->orderBy('nombre', 'asc')
									->take(10)
									->get();
		foreach ($clients as $query)
		{
		    $results[] = [ 'id' => $query->num_doc, 'value' => $query->datos_completo ];
		}
		return response()->json($results);
	}
}
