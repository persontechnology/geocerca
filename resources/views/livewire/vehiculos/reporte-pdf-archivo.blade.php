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
        @if ($ordenes->count()>0)
            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr>
                        
                        <th>N° Móvil</th>
                        <th>IMEI</th>
                        <th>Parquedero</th>
                        <th>Tipo</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Placa</th>
                        <th>Color</th>
                        <th>Tipo V.</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ordenes as $veh)
                        <tr>
                            <td>{{ $veh->numero_movil }}</td>
                            <td>{{ $veh->imei }}</td>
                            <td>{{ $veh->parqueadero->nombre }}</td>
                            <td>{{ $veh->tipo }}</td>
                            <td>{{ $veh->modelo }}</td>
                            <td>{{ $veh->marca }}</td>
                            <td>{{ $veh->placa }}</td>
                            <td>{{ $veh->color }}</td>
                            <td>{{ $veh->tipoVehiculo->nombre}}</td>
                            <td>{{ $veh->estado }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            @include('layouts.alert',['type'=>'info','msg'=>'No existe ordenes de movilización'])
        @endif
    </div>
    
</body>
</html>