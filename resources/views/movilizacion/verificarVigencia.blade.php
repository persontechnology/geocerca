<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verificar orden de movilización</title>
</head>
<body>
    <h1>
        Orden de movilización N°. <strong>{{ $om->numero }}, con el conductor {{ $om->conductor->apellidos_nombres }}:</strong>
    </h1>

    <br>
    <h1>
        <strong>{{ $estado==='si'?'ESTÁ DISPONIBLE':'NO ESTÁ DISPONIBLE' }}</strong>
    </h1>
</body>
</html>