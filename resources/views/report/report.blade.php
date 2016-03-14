<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link href="{{ URL::asset('css/bootstrap.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
        
        .bordes {
            padding: 1cm;
        }

    </style>
</head>
<body id="app-layout">

    <div class="container-fluid">
        @foreach ($taxes as $taxs)
            <?php 
                $total=0;
                $nombre=$taxs[0]->datos_completo;
                $num_doc=$taxs[0]->num_doc;
                $direccion=$taxs[0]->direccion;
                $predio_id=$taxs[0]->predio_id;
                $tributos_padre=collect($taxs)->groupBy('derecho_emision_grupo_id');
            ?>
            
            <table width="100%">
                <tr>
                    <td class="text-center" colspan="2">
                        <h2>Recibo</h2>
                    </td>
                </tr>
            
                <tr>
                    <td><strong>Nombre y/o Razon Social: </strong></td>
                    <td>{{$nombre}}</td>
                </tr>
                <tr>
                    <td><strong>DNI y/o RUC: </strong></td>
                    <td>{{$num_doc}}</td>
                </tr>
                <tr>
                    <td><strong>Direccion: </strong></td>
                    <td>{{$direccion}}</td>
                </tr>
                <tr>
                    <td><strong>Predio id: </strong></td>
                    <td>{{$predio_id}}</td>
                </tr>
            </table>
            <table class="table">
                <thead>
                    <tr>
                        <th>Conceptos</th>
                        <th>Mes</th>
                        <th>Emisión</th>
                        <th>Vencimiento</th>
                        <th>Deuda</th>
                        <th>Importe</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tributos_padre as $tributos)
                        <?php 
                            $temp=0; 
                            $deuda=0; 
                            $importe=0;
                            $tax_name='';
                        ?>
                        @foreach ($tributos as $tributo)
                            @if ($temp === 0)
                                <?php 
                                    $importe= floatval($tributo->importe_cuota); 
                                    $deuda= -1*$importe;
                                    $tax_name=$tributo->derecho_emision_grupo_desc;
                                    $temp=1;
                                ?>
                            @endif
                            <?php 
                                $deuda=$deuda+floatval($tributo->importe_cuota);
                            ?>
                        
                            <tr>
                                <td>
                                    {{ $tributo->derecho_emision_grupo_desc }}
                                </td>
                                <td>
                                    {{ $tributo->cuota.'-'.$tributo->ano_aplicacion }}
                                </td>
                                <td>
                                    {{ date('d-m-Y', strtotime("{$tributo->fecha_generacion}")) }}
                                </td>
                                <td>
                                    {{ date('d-m-Y', strtotime("{$tributo->fecha_vencimiento}")) }}
                                    
                                </td>
                                <td class="text-right">
                                    {{ floatval($tributo->importe_cuota) }}
                                </td>
                                <td class="text-right">
                                    {{ $importe }}
                                </td>
                                <td class="text-right">
                                    {{ $importe+$deuda }}
                                </td>
                            </tr>
                        @endforeach
                        <?php $total=$total+$importe+$deuda; ?>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <strong>Total</strong>
                        </td>
                        <td class="text-right">
                            {{$total}}
                        </td>
                    </tr>
                </tfoot>
                    
            </table>  
            <div style="page-break-before:always;"></div>
        @endforeach
        
    </div>
    <script src="{{ URL::asset('js/vendor/jquery-1.12.0.js') }}"></script>
    <script src="{{ URL::asset('js/vendor/bootstrap.js') }}"></script>
</body>
</html>
