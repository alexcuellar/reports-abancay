@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <form action="{{ url('clients') }}" method="POST">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                        <div class="input-group col-md-12">
                            
                            <input type="text" id="autocomplete" class="search-query form-control" name="search" placeholder="Search" />
                            <span class="input-group-btn">
                                <button class="btn btn-danger"  type="submit">
                                    <span class=" glyphicon glyphicon-search"></span>
                                </button>
                            </span>
                            
                        </div>
                    </form>
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Apellido Paterno</th>
                                <th>Apellido Materno</th>
                                <th>Reporte</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($clients) > 0)
                                @foreach ($clients as $client)
                                    <tr>
                                        <td>{{ $client->num_doc }}</td>
                                        <td>{{ $client->nombre }}</td>
                                        <td>{{ $client->apellido_paterno }}</td>
                                        <td>{{ $client->apellido_materno }}</td>
                                        <td>
                                            <form action="{{ url('client/'.$client->num_doc)}}" method="POST">
                                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                                                <button class="btn btn-primary">Reporte</button>
                                            </form>
                                        </td>
                                    </tr>
                                    
                                @endforeach
                            @endif
                                
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
