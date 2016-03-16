<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            //$table->increments('id');
            //$table->timestamps();
            $table->integer('cta_cte_renta_id');
            $table->integer('tributo');
            $table->integer('ano_aplicacion');
            $table->date('fecha_generacion');
            $table->double('base_imponible', 18, 3);
            $table->char('estado_proceso', 1);
            $table->double('insoluto', 18, 3);
            $table->char('generado', 1);
            $table->integer('propietario_id');
            $table->integer('predio_expediente_id');
            $table->integer('predio_id');
            $table->char('siglas', 3);
            $table->char('derecho_emision_grupo_id',1);
            $table->char('derecho_emision_grupo_desc', 60);
            $table->integer('cuota');
            $table->integer('numero_recibo_emision');
            $table->double('importe_cuota', 15, 3);
            $table->double('importe_emision', 15, 3);
            $table->date('fecha_vencimiento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('taxes');
    }
}
