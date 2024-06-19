
    <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-lg">

        <!-- Expand button -->
        <button type="button" class="btn btn-sidebar-expand sidebar-control sidebar-secondary-toggle">
            <i class="icon-arrow-right13"></i>
        </button>
        <!-- /expand button -->

       
        <!-- Sidebar content -->
        <div class="sidebar-content">

            <!-- Header -->
            <div class="sidebar-section sidebar-section-body d-flex align-items-center">
                <h5 class="mb-0">Selecione vehículo</h5>
                <div class="ml-auto">
                    <button type="button" class="btn btn-outline-light text-body border-transparent btn-icon rounded-pill btn-sm sidebar-control sidebar-secondary-toggle d-none d-lg-inline-flex">
                        <i class="icon-transmission"></i>
                    </button>

                    <button type="button" class="btn btn-outline-light text-body border-transparent btn-icon rounded-pill btn-sm sidebar-mobile-secondary-toggle d-lg-none">
                        <i class="icon-cross2"></i>
                    </button>
                </div>
            </div>
            <!-- /header -->
            
                <!-- Sidebar search -->
                <div class="sidebar-section">
                    
                    <div class="mx-2">
                        <label for="">Buscar por N° móvil o placa</label>
                        <input wire:model="search" class="form-control" type="search" placeholder="..." autofocus>
                    </div>

                </div>
                <!-- /sidebar search -->
                
                <div class="sidebar-section">
                    <div class="collapse show" id="sidebar-users">
                        <div class="sidebar-section-body">
                            
                            <ul class="media-list media-list-bordered" id="external-events-list">
                            @if ($vehiculos->count()>0)
                                
                            
                                @foreach ($vehiculos as $vehiculo)
                                    <li class="media" style="cursor: move;" 
                                        data-id="{{ $vehiculo->id }}" 
                                        data-numeromovil="{{ $vehiculo->numero_movil }}" 
                                        data-marca="{{ $vehiculo->marca }}"
                                        data-modelo="{{ $vehiculo->modelo }}"
                                        data-placa="{{ $vehiculo->placa }}"
                                        data-tipo="{{ $vehiculo->tipoVehiculo->nombre??'' }}"
                                        data-color="{{ $vehiculo->color }}"
                                        data-conductorid="{{ $vehiculo->id_conductor }}"
                                        data-conductorinfo="{{ $vehiculo->conductor->apellidos_nombres??'' }}"
                                        data-numeroocupantes="{{ $vehiculo->numero_ocupantes }}"
                                        data-procedencia="{{ $vehiculo->procedencia }}"
                                        data-destino="{{ $vehiculo->destino }}"
                                        data-comisioncumplir="{{ $vehiculo->comision_cumplir }}"
                                        data-actividadcumplir="{{ $vehiculo->actividad_cumplir }}"
                                        data-direccion_id="{{ $vehiculo->direccion_id }}"
                                        data-departamento_id="{{ $vehiculo->direccion->departamento_id??0 }}"
                                    >

                                        @if (Storage::exists($vehiculo->foto))
                                            <img src="{{ Storage::url($vehiculo->foto) }}" width="36" height="36" class="rounded-circle" alt="">
                                        @endif
                                        <div class="media-body">
                                            
                                                <strong>{{ $vehiculo->numero_movil }}</strong> {{ $vehiculo->placa }}
                                            
                                            <span class="font-size-xs text-muted d-block">
                                                {{ $vehiculo->modelo }} {{ $vehiculo->color }}
                                            </span>
                                        </div>
                                        <div class="ml-3 align-self-center">
                                            <span data-popup="tooltip" title="{{ $vehiculo->estado }}" data-placement="right" class="badge badge-mark border-{{ $vehiculo->color_estado }}"></span>
                                        </div>
                                    </li>
                                @endforeach    
                                
                            @else
                            <i class="alert-danger" role="alert">No existe vehículo: {{ $search }}</i>
                            @endif
                        </ul>    

                        </div>
                    </div>
                </div>
        </div>
        <!-- /sidebar content -->
        
    </div>


