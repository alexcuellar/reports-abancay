<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
	public $timestamps = false;
	public $id = false;
    protected $fillable = [
    	'cta_cte_renta_id', 'tributo','ano_aplicacion', 'fecha_generacion', 'estado_proceso',
    	'insoluto', 'generado', 'propietario_id', 'predio_expediente_id', 'predio_id', 'siglas',
    	'derecho_emision_grupo_id', 'derecho_emision_grupo_desc', 'cuota', 'numero_recibo_emision',
    	'importe_cuota', 'importe_emision', 'fecha_vencimiento',
    ];
}
