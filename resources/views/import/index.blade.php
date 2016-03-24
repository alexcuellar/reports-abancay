@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <form action="{{ url('import') }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                        <select class="form-control" name="table">
                            <option value="persons">Persona</option>
                            <option value="taxes">Tributos</option>
                        </select>
                        <input type="file" name="file">
                        <button type="submit" class="btn btn-default btn-sm">
                            <span class="glyphicon glyphicon-download-alt"></span> Importar Datos
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection