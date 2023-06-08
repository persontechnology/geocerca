<div>
    
    <div class="card">
        <div class="card-header bg-transparent header-elements-sm-inline">
            <div class="card-title">
                <button class="btn btn-link" wire:click="pdf">
                    Descargar PDF <i class="fa-solid fa-file-pdf ml-1"></i>
                </button>
            </div>
        </div>
        <div class="card-header">
           

            <div class="form-row">
                <div class="form-group col-md-3">
                    <input type="search" wire:model="NumeroOrden" class="form-control" id="inputZip" placeholder="Buscar por # movil">
                </div>

                <div class="form-group col-md-3">
                    <select id="inputState" class="form-control" wire:model="IdParqueadero">
                        <option value="">Filtrar por parqueadero</option>
                        @foreach ($parqueaderos as $pa)
                            <option value="{{ $pa->id }}">{{ $pa->nombre }}</option>
                        @endforeach
                    </select>
                    
                </div>

                <div class="form-group col-md-3">
                    <select id="inputState" class="form-control" wire:model="IdTipoVehiculo">
                        <option value="">Filtrar por tipo de Vehículo</option>
                        @foreach ($tipoVehiculos as $tv)
                            <option value="{{ $tv->id }}">{{ $tv->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class='form-group col-md-3'>
                    <div class="form-group">
                        {{-- <label for="exampleFormControlInput2">Mostrar hasta</label> --}}
                        <select name="" id="" wire:model="mostrar" class="form-control">
                            <option value="10">10 registros</option>
                            <option value="50">50 registros</option>
                            <option value="100">100 registros</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                @if ($ordenMovilizaciones->count()>0)
                    <table class="table table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Acción	</th>
                                <th>Foto</th>
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
                            @foreach ($ordenMovilizaciones as $veh)
                                <tr>
                                    <td>
                                        @include('vehiculos.action',['vehiculo'=>$veh])
                                    </td>
                                    <td>
                                        @include('vehiculos.foto',['vehiculo'=>$veh])
                                    </td>
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
        </div>
        
        <div class="card-footer bg-white">
            {{ $ordenMovilizaciones->links() }}
        </div>    
        
    </div>
</div>