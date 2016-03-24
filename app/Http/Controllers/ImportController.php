<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Excel;
use Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\Tax;
use Zipper;

class ImportController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function index()
	{
		return view('import.index');
	}
	
	public function import(Request $request)
	{
		$rules = array(
	        'file' => 'required',
	        
	    );
	
	    $validator = Validator::make($request->all(), $rules);
	    $temp_file = tempnam(sys_get_temp_dir(), $request->table);
	    if ($validator->fails()) 
	    {
	        return Redirect::to('import')->withErrors($validator);
	    }
	    else 
	    {
	        try 
	        {
	        	$zip_file=$request->file('file')->getRealPath();
	        	$zipper=Zipper::make($zip_file);
	        	$zipper->extractTo('/tmp');
	        	//$set_csv = fopen($temp_file, "w");
	        	//fwrite($set_csv, $zipper->getFileContent('export.csv'));
	        	//fclose($set_csv);
	        	//dd(sys_get_temp_dir().'/export.csv');
	        	if ($request->table=='taxes'){
	        		DB::table('taxes')->delete();
	        		Excel::load(sys_get_temp_dir().'/export.csv', function ($reader) {
	        			foreach ($reader->get() as $row) {
	        				dd($row);
	        				$data=[
	        						"cta_cte_renta_id" => intval($row->cta_cte_renta_id),
	        						"tributo" => intval($row->tributo),
	        						"ano_aplicacion" => intval($row->ano_aplicacion),
	        						"fecha_generacion" => date_create_from_format('d/m/y', $row->fecha_generacion)->format('Y-m-d'),
	        						"estado_proceso" => $row->estado_proceso,
	        						"insoluto" => $row->insoluto,
	        						"generado" => $row->generado,
	        						"propietario_id" => intval($row->propietario_id),
	        						"predio_expediente_id" => intval($row->predio_expediente_id),
	        						"predio_id" => intval($row->predio_id),
	        						"siglas" => $row->siglas,
	        						"derecho_emision_grupo_id" => intval($row->derecho_emision_grupo_id),
	        						"derecho_emision_grupo_desc" => $row->derecho_emision_grupo_desc,
	        						"cuota" => intval($row->cuota),
	        						"numero_recibo_emision" => intval($row->numero_recibo_emision),
	        						"importe_cuota" => $row->importe_cuota,
	        						"importe_emision" => $row->importe_emision,
	        						"fecha_vencimiento" => date_create_from_format('d/m/y', $row->fecha_vencimiento)->format('Y-m-d'),
	        				];
	        				Tax::create($data);
		        			
				    	}
				    		
				    });
			    }
			    if ($request->table=='persons'){
		        	Excel::load($temp_file, function ($reader) {
		        		foreach ($reader->get() as $row) {
		        			Tax::create($row);
		        		}
				    });
			    }
	        	unlink($temp_file);
	        	$message=array(
				    "type" => "Successfully",
				    "message" => 'Uploaded successfully',
				);
	        	return view('import.message', ['message' => $message]);
	        } catch (\Exception $e) {
	        	unlink($temp_file);
	        	$message=array(
				    "type" => "Error",
				    "message" => $e->getMessage(),
				);
				return view('import.message', ['message' => $message]);
	        }
	    } 
		
	}
}
