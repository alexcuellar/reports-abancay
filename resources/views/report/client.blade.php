@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            @foreach ($taxes as $taxs)
                                <?php 
                                    //dd(date_create_from_format('d/m/y', '29/02/16')->format('d-m-Y'));
                                    $fecha_creacion=date('d-m-Y', strtotime("$fecha"));
                                    $total=0;
                                    $nombre=$taxs[0]->datos_completo;
                                    $num_doc=$taxs[0]->num_doc;
                                    $direccion=$taxs[0]->direccion;
                                    $predio_id=$taxs[0]->predio_id;
                                    $tributos_padre=collect($taxs)->groupBy('derecho_emision_grupo_id');
                                    $lines=array();
                                    $count=0;
                                ?>
                                    @foreach ($tributos_padre as $tributos)
                                        <?php 
                                            $temp=0; 
                                            $deuda=0; 
                                            $importe=0;
                                            $interes=0;
                                            $tax_name='';
                                            $ano_aplicacion='';
                                            $fecha_generacion='';
                                            $fecha_vencimiento='';
                                        ?>

                                        @foreach ($tributos as $tributo)
                                            @if ($temp === 0)
                                                <?php
                                                    $importe= floatval($tributo->importe_cuota); 
                                                    $tax_name=$tributo->derecho_emision_grupo_desc;
                                                    $temp=1;
                                                    $ano_aplicacion=$tributo->cuota.'-'.$tributo->ano_aplicacion;
                                                    $fecha_generacion=date('d-m-Y', strtotime("{$tributo->fecha_generacion}"));
                                                    $fecha_vencimiento=date('d-m-Y', strtotime("{$tributo->fecha_vencimiento}"));
                                                    if (strtotime($fecha_creacion)>strtotime("$tributo->fecha_vencimiento"))
                                                    {
                                                        $interes=$interes+round($importe*floatval($charges[intval($tributo->cuota)-1]->tim)/100,2);
                                                    }
                                                ?>
                                            @else
                                                <?php
                                                    $deuda= $deuda + floatval($tributo->importe_cuota); 
                                                    if (strtotime($fecha_creacion)>strtotime("$tributo->fecha_vencimiento"))
                                                        {
                                                            $interes=$interes+round(floatval($tributo->importe_cuota)*floatval($charges[intval($tributo->cuota)-1]->tim)/100,2);
                                                        }
                                                ?>
                                            @endif
                                            <?php 
                                                $lines[$count] = array('deuda' => $deuda, 'importe' => $importe, 
                                                'tax_name' => $tax_name, 'ano_aplicacion' => $tributo->cuota.'-'.$tributo->ano_aplicacion, 
                                                'fecha_generacion' => date('d-m-Y', strtotime("{$tributo->fecha_generacion}"))  , 'fecha_vencimiento' => date('d-m-Y', strtotime("{$tributo->fecha_vencimiento}")), 
                                                'interes'=>$interes);
                                                $count++;
                                            ?>
                                        @endforeach
                                        <?php 
                                            $total=$total+$importe+$deuda+$interes; 
                                        ?>
                                    @endforeach
                                    
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
                                            <th>Interes</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lines as $line)
                                            <tr>
                                                <td>
                                                    {{ $line['tax_name'] }}
                                                </td>
                                                <td>
                                                    {{ $line['ano_aplicacion'] }}
                                                </td>
                                                <td>
                                                    {{ $line['fecha_generacion'] }}
                                                </td>
                                                <td>
                                                    {{ $line['fecha_vencimiento'] }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $line['deuda'] }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $line['importe'] }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $line['interes'] }}
                                                </td>
                                                <td class="text-right">
                                                    {{ $line['importe']+$line['deuda'] }}
                                                </td>
                                            </tr>
                                            
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="7">
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
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection