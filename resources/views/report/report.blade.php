<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link href="{{ URL::asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/a5.css') }}" rel="stylesheet">
    
</head>
<body id="app-layout">

    @foreach ($taxes as $taxs)
        <?php 
            $total=0;
            $nombre=$taxs[0]->datos_completo;
            $num_doc=$taxs[0]->num_doc;
            $direccion=$taxs[0]->direccion;
            $predio_id=$taxs[0]->predio_id;
            $tributos_padre=collect($taxs)->groupBy('derecho_emision_grupo_id');
            $fecha_generacion=date('d-m-Y', strtotime("{$taxs[0]->fecha_generacion}"));
            $fecha_vencimiento=date('d-m-Y', strtotime("{$taxs[0]->fecha_vencimiento}"));
            $mes_actual=$taxs[0]->cuota;
            $tax_code_bc= array();
            $tax_code_rs= array();
            $tax_code_pj= array();
        ?>
        <header>
            <table>
                <tr>
                    <td style="padding-left: 33mm; padding-top: 3mm;">{{ $predio_id.$mes_actual }}</td> 
                </tr>
                <tr>
                    <td style="padding-left: 140mm; padding-top: 2mm;">{{$fecha_generacion}}</td>
                </tr>
                <tr>
                    <td style="padding-left: 140mm;">{{$fecha_vencimiento}}</td>
                </tr>
                <tr>
                    <td style="padding-left: 143mm; padding-top: 2.5mm;">{{$mes_actual}}</td>
                </tr>
            </table>
        </header>
            
        <div style="height: 20mm;">
            <table>
                <tr>
                    <td style="padding-left: 15mm; padding-top: 8.5mm;">{{$predio_id}}</td> 
                </tr>
                <tr>
                    <td style="padding-left: 35mm;">{{$nombre}}</td>
                </tr>
                <tr>
                    <td style="padding-left: 18mm;">{{$num_doc}}</td>
                </tr>
                <tr>
                    <td style="padding-left: 17mm;">{{$direccion}}</td>
                </tr>
            </table>
        </div>
        <div style="height: 20mm; margin-top: 33mm; margin-bottom: 40mm;">
            <table>
                @foreach ($tributos_padre as $tributos)
                    <?php 
                        $temp=0; 
                        $deuda=0; 
                        $importe=0;
                        $tax_name='';
                        $ano_aplicacion='';
                        $fecha_generacion='';
                        $fecha_vencimiento='';
                    ?>
                    @foreach ($tributos as $tributo)
                        @if ($temp === 0)
                            <?php 
                                $importe= floatval($tributo->importe_cuota); 
                                $deuda= -1*$importe;
                                $tax_name=$tributo->derecho_emision_grupo_desc;
                                $temp=1;
                                $ano_aplicacion=$tributo->cuota.'-'.$tributo->ano_aplicacion;
                                $fecha_generacion=date('d-m-Y', strtotime("{$tributo->fecha_generacion}"));
                                $fecha_vencimiento=date('d-m-Y', strtotime("{$tributo->fecha_vencimiento}"));
                            ?>
                        @endif
                        <?php 
                            $deuda=$deuda+floatval($tributo->importe_cuota);
                        ?>
                    
                    @endforeach
                    <?php 
                        if (trim($tributo->siglas)=="BC") {
                            $tax_code_bc[0]=$deuda ;
                            $tax_code_bc[1]="" ;
                            $tax_code_bc[2]=$importe ;
                            $tax_code_bc[3]=$importe+$deuda ;
                        }
                        if (trim($tributo->siglas)=="RS") {
                            $tax_code_rs[0]=$deuda ;
                            $tax_code_rs[1]="" ;
                            $tax_code_rs[2]=$importe ;
                            $tax_code_rs[3]=$importe+$deuda ;
                        }
                        if (trim($tributo->siglas)=="PJ") {
                            $tax_code_pj[0]=$deuda ;
                            $tax_code_pj[1]="" ;
                            $tax_code_pj[2]=$importe ;
                            $tax_code_pj[3]=$importe+$deuda ;
                        }
                        $total=$total+$importe+$deuda; 
                    ?>

                @endforeach
                @if (count($tax_code_rs)>0)
                    <tr>
                        <td style="width: 40mm;"></td>
                        <td style="width: 46mm;" class="text-right">{{$tax_code_rs[0] }}</td>
                        <td style="width: 20mm;" class="text-right">{{$tax_code_rs[1] }}</td>
                        <td style="width: 21.5mm;" class="text-right">{{$tax_code_rs[2] }}</td>
                        <td class="text-right" style="padding-right: 2mm;">{{$tax_code_rs[3] }}</td>
                    </tr>
                @else
                    <tr>
                        <td style="width: 40mm;" class="text-right"></td>
                        <td style="width: 46mm;" class="text-right">{{ "0.0" }}</td>
                        <td style="width: 20mm;" class="text-right"></td>
                        <td style="width: 21.5mm;" class="text-right">{{ "0.0" }}</td>
                        <td class="text-right" style="padding-right: 2mm;">{{ "0.0" }}</td>
                    </tr>
                @endif
                @if (count($tax_code_pj)>0)
                    <tr>
                        <td style="width: 40mm;" class="text-right"></td>
                        <td style="width: 46mm;" class="text-right">{{$tax_code_pj[0] }}</td>
                        <td style="width: 20mm;" class="text-right">{{$tax_code_pj[1] }}</td>
                        <td style="width: 21.5mm;" class="text-right">{{$tax_code_pj[2] }}</td>
                        <td class="text-right" style="padding-right: 2mm;">{{$tax_code_pj[3] }}</td>
                    </tr>
                @else
                    <tr>
                        <td style="width: 40mm;" ></td>
                        <td style="width: 46mm;" class="text-right">{{ "0.0" }}</td>
                        <td style="width: 20mm;" class="text-right"></td>
                        <td style="width: 21.5mm;" class="text-right">{{ "0.0" }}</td>
                        <td class="text-right" style="padding-right: 2mm;">{{ "0.0" }}</td>
                    </tr>
                @endif

                @if (count($tax_code_bc)>0)
                    <tr>
                        <td style="width: 40mm;" class="text-right"></td>
                        <td style="width: 46mm;" class="text-right">{{$tax_code_bc[0] }}</td>
                        <td style="width: 20mm;" class="text-right">{{$tax_code_bc[1] }}</td>
                        <td style="width: 21.5mm;" class="text-right">{{$tax_code_bc[2] }}</td>
                        <td class="text-right">{{$tax_code_bc[3] }}</td>
                    </tr>
                @else
                    <tr>
                        <td style="width: 40mm;" class="text-right"></td>
                        <td style="width: 46mm;" class="text-right">{{ "0.0" }}</td>
                        <td style="width: 20mm;" class="text-right"></td>
                        <td style="width: 21.5mm;" class="text-right">{{ "0.0" }}</td>
                        <td class="text-right" style="padding-right: 2mm;">{{ "0.0" }}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="5" class="text-right" style="padding-top: 2mm; padding-right: 2mm;">{{ $total }}</td>
                </tr>
            </table>   
        </div>

        <footer>
            <table>
                <tr>
                    <td style="width: 135mm; max-width: 135mm; padding-top: 18mm;">
                        <table>
                            <tr>
                                <td style="padding-left: 15mm; padding-top: 2mm;">
                                    {{$predio_id}}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 33mm; padding-top: 2mm;">
                                     {{$nombre}}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 18mm; padding-top: 1mm;">
                                    {{$num_doc}}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 15mm; padding-top: 1.5mm; padding-right: 20mm;">
                                    {{$direccion}}
                                </td>
                            </tr>
                        </table>   
                    </td>
                    <td style="padding-top: 10mm;">
                        <table>
                            <tr>
                                <td>
                                    {{ $predio_id.$mes_actual }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <br />
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 2mm;">
                                    {{$fecha_generacion}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{$fecha_vencimiento}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{$mes_actual}}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 2mm;">
                                    {{$fecha_vencimiento}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $total }}
                                </td>
                            </tr>
                        </table>   

                    </td>
                </tr>
            </table> 

        </footer>
          
        <div style="page-break-before:always;"></div>
    @endforeach

    <script src="{{ URL::asset('js/vendor/jquery-1.12.0.js') }}"></script>
    <script src="{{ URL::asset('js/vendor/bootstrap.js') }}"></script>
</body>
</html>
