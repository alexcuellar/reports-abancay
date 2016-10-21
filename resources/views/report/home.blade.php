@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <form action="{{ url('reports') }}" method="POST">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

                        <!--select class="form-control" name="tipo">
                            <option value="43" selected>RECOJO DE RESIDUOS SOLIDOS</option>
                            <option value="357">PARQUES Y JARDINES</option>
                        </select-->
                        <p>Fecha: <input type="text" name="fecha" id="datepicker"></p>
                        <p>Direccion: <input type="text" name="direccion"></p>
                        <button type="submit" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-download-alt"></span> Descargar Recibos
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection