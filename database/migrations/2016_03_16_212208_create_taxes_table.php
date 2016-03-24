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
            $table->increments('id');
            $table->integer('cta_cte_renta_id');
            $table->integer('tributo');
            $table->integer('ano_aplicacion');
            $table->date('fecha_generacion');
            $table->char('estado_proceso', 1);
            $table->float('insoluto');
            $table->char('generado', 1);
            $table->integer('propietario_id');
            $table->integer('predio_expediente_id');
            $table->integer('predio_id');
            $table->char('siglas', 3);
            $table->char('derecho_emision_grupo_id',1);
            $table->char('derecho_emision_grupo_desc', 60);
            $table->integer('cuota');
            $table->integer('numero_recibo_emision');
            $table->float('importe_cuota');
            $table->float('importe_emision');
            $table->date('fecha_vencimiento');
            $table->timestamps();
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
