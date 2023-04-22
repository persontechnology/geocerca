
@if ($om->estado==='RECORRIDO')
@php
    $estado=$om->lecturas()->latest()->first()->estado??''
@endphp
<span class="badge badge-{{ $estado==='DENTRO'?'success':'warning' }}">RRECORRIDO {{ $estado }}</span>
@else
<span class="badge badge-{{ $om->color_estado }}">{{ $om->estado }}</span>
@endif