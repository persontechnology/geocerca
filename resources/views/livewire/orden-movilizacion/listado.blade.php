<div>
    
    <div class="card">
        <div class="card-header bg-transparent header-elements-sm-inline">
            <div class="card-title">
                <button class="btn btn-link" wire:click="pdf">
                    Descargar PDF <i class="fa-solid fa-file-pdf ml-1"></i>
                </button>
            </div>
        
            @include('movilizacion.estados')
        </div>
        <div class="card-header">
            <div class="form-row">
                <div class='form-group col-md-5'>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Desde</label>
                        <input type="date" class="form-control" wire:model="desde" id="exampleFormControlInput1" >
                    </div>
                </div>
                <div class='form-group col-md-5'>
                    <div class="form-group">
                        <label for="exampleFormControlInput2">Hasta</label>
                        <input type="date" class="form-control" wire:model="hasta" id="exampleFormControlInput2" >
                    </div>
                </div>
                <div class='form-group col-md-2'>
                    <div class="form-group">
                        <label for="exampleFormControlInput2">Mostrar hasta</label>
                        <select name="" id="" wire:model="mostrar" class="form-control">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-3">
                    <input type="search" wire:model="NumeroOrden" class="form-control" id="inputZip" placeholder="Buscar por # orden">
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


                <div class="form-group col-md-3">
                    <select id="inputState" class="form-control" wire:model="EstadoOrdenMovilizacion">
                        <option value="">Filtrar por estado</option>
                        <option value="SOLICITADO">SOLICITADO</option>
                        <option value="ACEPTADA">ACEPTADA</option>
                        <option value="DENEGADA">DENEGADA</option>
                        <option value="EJECUCIÓN FUERA">EJECUCIÓN FUERA</option>
                        <option value="EJECUCIÓN DENTRO">EJECUCIÓN DENTRO</option>
                        <option value="FINALIZADO">FINALIZADO</option>
                        <option value="INCUMPLIDA">INCUMPLIDA</option>
                        <option value="FUERA DE HORARIO">FUERA DE HORARIO</option>

                    </select>
                </div>
            </div>
            @if (!empty($selecionados))
                <button class="btn btn-primary" wire:click="pdfSelecionados">
                    Descargar opción 1 <i class="fa-solid fa-file-pdf ml-1"></i>
                </button>
                <button class="btn btn-info" wire:click="pdfSelecionadosADetalle">
                    Descargar opción 2 <i class="fa-solid fa-file-pdf ml-1"></i>
                </button>
                <i>{{ count($selecionados)  }} selecionados</i>
            @endif
        </div>
        
        <div class="card-body">
            
            
            <div class="table-responsive" style="leng">
                @if ($ordenMovilizaciones->count()>0)
                    <table class="table table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th>-</th>
                                <th>N° orden</th>
                                <th>N° ocupantes</th>
                                <th>N° movil placa</th>
                                <th>Fecha salida</th>
                                <th>Fecha retorno</th>
                                <th>Procedencia</th>
                                <th>Destino</th>
                                <th>Comisión a cumplir</th>
                                <th>Estado</th>
                                <th>Chofer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ordenMovilizaciones as $com)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                        <input wire:model="selecionados" class="form-check-input" type="checkbox" value="{{ $com->id }}" id="orden{{ $com->id }}">
                                        </div>
                                    </td>
                                    <td>{{ $com->numero }}</td>
                                    <td>{{ $com->numero_ocupantes }}</td>
                                    <td>
                                        @if (Storage::exists($com->vehiculo->foto))
                                            <a href="{{ Storage::url($com->vehiculo->foto) }}"><img src="{{ Storage::url($com->vehiculo->foto) }}" class="rounded-circle" width="32" height="32" alt="">    </a>
                                        @endif
                                        
                                        {{ $com->vehiculo->numero_movil_placa??'' }}
                                    </td>
                                    
                                    <td>
                                        {{ $com->fecha_salida }}
                                    </td>
                                    <td>
                                        {{ $com->fecha_retorno }}
                                    </td>
                                    <td>{{ $com->procedencia }}</td>
                                    <td>{{ $com->destino }}</td>
                                    <td>{{ Str::limit($com->comision_cumplir, 25, '...') }}</td>
                                    <td>
                                    <span class="badge badge-{{ $com->color_estado }}">{{ $com->estado }}</span>
                                    </td>
                                    <td>
                                        {{ $com->conductor->apellidos_nombres??'' }}
                                    </td>
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