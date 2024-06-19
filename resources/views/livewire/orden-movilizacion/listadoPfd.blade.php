<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
    
    
        table , td, th {
            width: 100%;
            border: 1px solid #595959;
            border-collapse: collapse;
            text-align: center;
        }
        td, th {
            padding: 2px;
            width: 30px;
            height: 25px;
            font-size: 12px;
        }

    
        .fotoEvidencia {
            background-repeat: no-repeat;
            background-size: 100% 100%;
            height: 95px;
            
        }  
    
        
    </style>
</head>
<body>
    <div style="padding-top: 5px;">
        <table>
            
            <tbody>
                <tr>
                    <td><strong>#</strong></td>
                    <td><strong>N° O.M</strong></td>
                    <td><strong>Vehículo</strong></td>
                    <td><strong>Conductor</strong></td>
                    <td><strong>Fecha Salida</strong></td>
                    <td><strong>Fecha Retorno</strong></td>
                    @if (Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('SiteAdmin'))
                    <td><strong>Estado</strong></td>    
                    @endif
                    


                    <td><strong># ocupantes</strong></td>
                    <td><strong>Procedencia</strong></td>
                    <td><strong>Destino</strong></td>
                    <td><strong>Comisión Cumplir</strong></td>
                    
                    <td><strong>Solicitante</strong></td>
                    <td><strong>Autorizado</strong></td>
                    <td><strong>Departamento</strong></td>
                    <td><strong>Dirección</strong></td>
                    <td><strong>Firma conductor</strong></td>
                </tr>
               @php
                   $i=1;
               @endphp
                @foreach ($ordenes as $orden)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $orden->numero }}</td>
                    <td>{{ $orden->vehiculo->numero_movil_placa }}</td>
                    <td>{{ $orden->conductor->apellidos_nombres??'' }}</td>
                    <td>{{ $orden->fecha_salida }}</td>
                    <td>{{ $orden->fecha_retorno }}</td>
                    
                    @if (Auth::user()->hasRole('SuperAdmin') || Auth::user()->hasRole('SiteAdmin'))
                    <td>{{ $orden->estado }}</td>
                    @endif


                    <td>{{ $orden->numero_ocupantes }}</td>
                    <td>{{ $orden->procedencia }}</td>
                    <td>{{ $orden->destino }}</td>
                    <td>{{ $orden->comision_cumplir }}</td>
                    
                    
                    <td>{{ $orden->solicitante->apellidos_nombres??'' }}</td>
                    <td>{{ $orden->autorizado->apellidos_nombres??'' }}</td>
                    <td>{{ $orden->direccion->departamento->nombre??'' }}</td>
                    <td>{{ $orden->direccion->nombre??'' }}</td>

                    <td style="margin: 0px; padding: 0px; width: 10%;">
                        {{-- @if ($orden->autorizado && $orden->autorizado->firma)
                        <div class="fotoEvidencia" style="margin: 1em; background: url({!! public_path( $orden->autorizado->firma_link) !!});"></div>
                        @endif --}}

                    </td>
                </tr>
                @endforeach
              
                
            </tbody>
        </table>
    </div>
    
</body>
</html>