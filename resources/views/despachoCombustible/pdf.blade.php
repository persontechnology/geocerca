<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table, td, th {
               border: 1px solid;
           }

           table {
               width: 100%;
               border-collapse: collapse;
           } 
   </style>
</head>
<body>
    <div class="container" style="padding-bottom: 5px;">
        @include('despachoCombustible.table',['dc'=>$dc,'fotoPdf'=>'SI'])
    </div>
</body>
</html>