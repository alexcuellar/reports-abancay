@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$message['type']}}</div>

                <div class="panel-body">
                    {{$message['message']}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection