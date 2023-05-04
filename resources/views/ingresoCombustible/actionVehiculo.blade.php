<div class="d-flex align-items-center">
    <input type="radio" 
    data-id="{{ $vehiculo->id }}" 
    data-numeromovil="{{ $vehiculo->numero_movil }}" 
    data-marca="{{ $vehiculo->marca }}"
    data-modelo="{{ $vehiculo->modelo }}"
    data-placa="{{ $vehiculo->placa }}"
    data-tipo="{{ $vehiculo->tipoVehiculo->nombre??'' }}"
    data-color="{{ $vehiculo->color }}"
    data-conductorid="{{ $vehiculo->id_conductor }}"
    data-conductorinfo="{{ $vehiculo->conductor->apellidos_nombres??'' }}"
    data-ultimokilometraje="{{ $vehiculo->ultimoKilometraje() }}"
    onchange="selecionarVehiculo(this)" name="vehiculo" id="id_ve_{{ $vehiculo->id }}">
</div>