@foreach($ordenes as $orden)
@include('movilizacion.pdf',['orden'=>$orden])
@endforeach
