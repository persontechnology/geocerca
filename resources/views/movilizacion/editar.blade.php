@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('odernMovilizacionEditar',$orden))
@section('barraLateral')
<div class="breadcrumb justify-content-center">
    <a href="{{ route('controlOdernMovilizacionPdf',$orden->id) }}" target="_blanck" class="breadcrumb-elements-item">
        Descargar PDF <i class="fa-solid fa-file-pdf ml-1"></i>
    </a>
</div>
@endsection


@section('content')
<form action="{{ route('odernMovilizacionActualizar') }}" method="POST" autocomplete="off" id="formOrdenMovilizacion">
    @csrf
    <input type="hidden" name="id_orden_parqueadero" value="{{ $orden->id }}" required>
    <div class="card">
        <div class="card-header">
            Estado de la orden: <span class="badge badge-{{ $orden->color_estado }}">{{ $orden->estado }}</span>
        </div>
        <div class="card-body">
          
            <fieldset>
                <legend class="font-weight-semibold">
                    NÚMERO DE ORDEN: <strong class="text-danger text-right" id="numero_orden_movilizacion">{{ $orden->numero }}</strong>
                </legend>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="fecha_solicitud">Fecha de solicitud:</label>
                            <input type="text" id="fecha_solicitud" name="fecha_solicitud" readonly onkeydown="event.preventDefault()" value="{{ $orden->created_at }}" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="fecha_salida">Fecha y hora de salida<i class="text-danger">*</i></label>
                                
                            <div class='input-group' id='datetimepicker1' data-td-target-input='nearest' data-td-target-toggle='nearest'>
                                
                                <input id='fecha_salida'   onkeydown="event.preventDefault()" name="fecha_salida" type='text' class="form-control @error('fecha_salida') is-invalid @enderror" value="{{ old('fecha_salida',$orden->fecha_salida)}}" data-td-target='#datetimepicker1' required/>
                                <span class='input-group-append' data-td-target='#datetimepicker1' data-td-toggle='datetimepicker'>
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                </span>
                                @error('fecha_salida')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror    
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="fecha_retorno">Fecha y hora de retorno<i class="text-danger">*</i></label>
                                
                            <div class='input-group' id='datetimepicker2' data-td-target-input='nearest' data-td-target-toggle='nearest'>
                                <input id='fecha_retorno'  onkeydown="event.preventDefault()" name="fecha_retorno" type='text' class="form-control @error('fecha_retorno') is-invalid @enderror" value="{{ old('fecha_retorno',$orden->fecha_retorno)}}" data-td-target='#datetimepicker2' required/>
                                <span class='input-group-append' data-td-target='#datetimepicker2' data-td-toggle='datetimepicker'>
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                </span>
                                @error('fecha_retorno')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label  for="numero_ocupantes">N° ocupantes<i class="text-danger">*</i></label>
                            <div class="input-group">
                                <input type="number"  name="numero_ocupantes" value="{{ old('numero_ocupantes',$orden->numero_ocupantes) }}" class="form-control @error('numero_ocupantes') is-invalid @enderror" id="numero_ocupantes" required>
                                @error('numero_ocupantes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

           


                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label  for="numeroMovil">N° Movil</label>
                            <div class="input-group">
                                
                                <input type="hidden" name="vehiculo" id="vehiculo" value="{{ old('vehiculo',$orden->vehiculo_id) }}" required>
                                <input type="text" data-opcion="vehiculo" onclick="modalConductorSolicitante(this)" data-toggle="modal" data-target="#modal_large_vehiculo" onkeydown="event.preventDefault()" name="numeroMovil" value="{{ old('numeroMovil',$orden->vehiculo->numero_movil) }}" class="form-control @error('vehiculo') is-invalid @enderror" id="numeroMovil" placeholder="Vehículo sin selecionar.!">
                                <span class="input-group-append">
                                    <span data-toggle="modal" data-target="#modal_large_vehiculo" class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                </span>
                                
                                @error('vehiculo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label  for="marca">Marca</label>
                            <div class="input-group">
                                <input type="text" readonly onkeydown="event.preventDefault()" name="marca" value="{{ old('marca',$orden->vehiculo->marca) }}" class="form-control" id="marca">
                                @error('marca')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label  for="modelo">Modelo</label>
                            <div class="input-group">
                                <input type="text" readonly onkeydown="event.preventDefault()" name="modelo" value="{{ old('modelo',$orden->vehiculo->modelo) }}" class="form-control @error('modelo') is-invalid @enderror" id="modelo">
                                @error('modelo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label  for="placa">Placa</label>
                            <div class="input-group">
                                <input type="text" readonly onkeydown="event.preventDefault()" name="placa" value="{{ old('placa',$orden->vehiculo->placa) }}" class="form-control @error('placa') is-invalid @enderror" id="placa">
                                @error('placa')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label  for="tipo">Tipo</label>
                            <div class="input-group">
                                <input type="text" readonly onkeydown="event.preventDefault()" name="tipo" value="{{ old('tipo',$orden->vehiculo->tipoVehiculo->nombre) }}" class="form-control" id="tipo">
                                @error('tipo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label  for="color">Color</label>
                            <div class="input-group">
                                <input type="text" readonly onkeydown="event.preventDefault()" name="color" value="{{ old('color',$orden->vehiculo->color) }}" class="form-control @error('color') is-invalid @enderror" id="color">
                                @error('color')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label  for="procedencia">Procedencia<i class="text-danger">*</i></label>
                            <div class="input-group">
                                <input type="text"  name="procedencia" value="{{ old('procedencia',$orden->procedencia) }}" class="form-control @error('procedencia') is-invalid @enderror" id="procedencia" required>
                                @error('procedencia')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label  for="destino">Destino<i class="text-danger">*</i></label>
                            <div class="input-group">
                                <input type="text"  name="destino" value="{{ old('destino',$orden->destino) }}" class="form-control @error('destino') is-invalid @enderror" id="destino" required>
                                @error('destino')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label  for="comision_cumplir">Comisión a cumplir<i class="text-danger">*</i></label>
                    
                    <div class="input-group">
                        <textarea name="comision_cumplir"  class="form-control @error('comision_cumplir') is-invalid @enderror" id="comision_cumplir" required>{{ old('comision_cumplir',$orden->comision_cumplir) }}</textarea>
                        @error('comision_cumplir')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="conductor_info">Datos del conductor<i class="text-danger">*</i></label>
                            <div class="input-group">
                                <input type="hidden" name="conductor" id="conductor" value="{{ old('conductor',$orden->conductor_id??'') }}">
                                <input type="text" data-opcion="conductor" onclick="modalConductorSolicitante(this);" onkeydown="event.preventDefault()"  id="conductor_info" name="conductor_info" value="{{ old('conductor_info',$orden->conductor->apellidos_nombres??'') }}" data-toggle="modal" data-target="#modal_large" class="form-control @error('conductor') is-invalid @enderror" placeholder="Seleccionar conductor.." required>
                                <span class="input-group-append">
                                    <span data-toggle="modal" data-target="#modal_large_na" class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                </span>
                            </div>

                            @error('conductor')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            
                            <label for="solicitante_info">Datos del solicitante / <small>solo el usuario Supervisor puede cambiar de solicitante</small></label>
                            <div class="input-group">
                                <input type="hidden" name="solicitante" id="solicitante" value="{{ old('solicitante',$orden->solicitante_id??'') }}">
                                <input type="text" data-opcion="solicitante" onclick="modalConductorSolicitante(this);" onkeydown="event.preventDefault()"  id="solicitante_info" name="solicitante_info" value="{{ old('solicitante_info',$orden->solicitante->apellidos_nombres??'') }}"  data-toggle="modal" data-target="#{{ Auth::user()->hasRole('Supervisor')?'modal_large_solicitante':'na' }}" class="form-control @error('solicitante') is-invalid @enderror" placeholder="Seleccionar solicitante..">
                                <span class="input-group-append">
                                    <span data-toggle="modal" data-target="#modal_large_na" class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                </span>
                            </div>

                            @error('solicitante')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="col-lg-12">
                        <div class="form-group">
                            <label for="estado">Estado<i class="text-danger">*</i></label>
                            <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" required>
                                <option value="SOLICITADO" {{ old('estado',$orden->estado)=='SOLICITADO'?'selected':'' }}>SOLICITADO</option>
                                <option value="ACEPTADA" {{ old('estado',$orden->estado)=='ACEPTADA'?'selected':'' }}>ACEPTADA</option>
                                <option value="DENEGADA" {{ old('estado',$orden->estado)=='DENEGADA'?'selected':'' }}>DENEGADA</option>
                                <option value="EJECUCIÓN DENTRO" {{ old('estado',$orden->estado)=='EJECUCIÓN DENTRO'?'selected':'' }}>EJECIÓN DENTRO</option>
                                <option value="EJECUCIÓN FUERA" {{ old('estado',$orden->estado)=='EJECUCIÓN FUERA'?'selected':'' }}>EJECIÓN FUERA</option>
                                <option value="FINALIZADO" {{ old('estado',$orden->estado)=='FINALIZADO'?'selected':'' }}>FINALIZADO</option>
                                <option value="INCUMPLIDA" {{ old('estado',$orden->estado)=='INCUMPLIDA'?'selected':'' }}>INCUMPLIDA</option>
                                <option value="FUERA DE HORARIO" {{ old('estado',$orden->estado)=='FUERA DE HORARIO'?'selected':'' }}>FUERA DE HORARIO</option>
                            </select>

                            @error('estado')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div> --}}

                </div>
            </fieldset>
            <p><strong>Información:</strong></p>
            <p>Creado por: {{ $orden->usuarioCreado->apellidos_nombres??'' }}</p>
            <p>Autorizado por: {{ $orden->autorizado->apellidos_nombres??'' }} </p>
            
        </div>
        <div class="card-footer text-muted">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a class="btn btn-danger" href="{{ route('odernMovilizacionListado') }}">Cancelar</a>
        </div>
    </div>
</form>




<!-- conductor modal -->
<div id="modal_large_solicitante" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalSolicitante"></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    {!! $solt->html()->table() !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="buttonModalSolicitante" onclick="seleccionarConductorSolicitante(this);" data-id="" data-user="" class="btn btn-primary" data-dismiss="modal"></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /conductor modal -->



<!-- conductor modal -->
<div id="modal_large" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalConductorSolicitante"></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    {!! $cont->html()->table() !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="buttonModalConductorSolicitante" onclick="seleccionarConductorSolicitante(this);" data-id="" data-user="" class="btn btn-primary" data-dismiss="modal"></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /conductor modal -->



<!-- vehiculo modal -->
<div id="modal_large_vehiculo" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalSeleccionarVehiculo">Seleccionar vehículo</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    {!! $veht->html()->table() !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /conductor modal -->

@push('scripts')
    {!! $veht->html()->scripts() !!} 
    {!! $cont->html()->scripts() !!} 
    {!! $solt->html()->scripts() !!} 
@endpush


@push('linksCabeza')
    <!-- Popperjs -->
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <!-- Tempus Dominus JavaScript -->
    <script src="{{ asset('js/tempus-dominus/dist/js/tempus-dominus.js') }}"></script>

    <!-- Tempus Dominus Styles -->
    <link rel="stylesheet" href="{{ asset('js/tempus-dominus/dist/css/tempus-dominus.css') }}">
    <script src="{{ asset('js/monent.js') }}"></script>
    

@endpush

@prepend('linksPie')
    <script>
        

        $('#formOrdenMovilizacion').submit(function(event) {
            event.preventDefault();
            var form = $(this)[0];

            $.confirm({
                theme: 'Modern',
                type: 'blue',
                closeIcon: true,
                icon: 'fa-solid fa-triangle-exclamation fa-beat',
                title: 'Confirmar!',
                content: 'Está seguro de actualizar orden de movilización.',
                buttons: {
                    confirmar: function() {
                        form.submit();
                    },
                    cancelar: function() {

                    }
                }
            });

        });

         // seleciona conductor o solicitante
        var conductorOSolictante="";
        function modalConductorSolicitante(arg){
            conductorOSolictante=$(arg).data('opcion');
            
            switch (conductorOSolictante) {
                case 'conductor':
                    $('#tituloModalConductorSolicitante').html('Selecionar conductor');
                    $('#buttonModalConductorSolicitante').html('Sin conductor');
                    break;
                case 'solicitante':
                    $('#tituloModalSolicitante').html('Selecionar solicitante');
                    $('#buttonModalSolicitante').html('Sin solicitante');
                    break;
            }
        }
        function seleccionarConductorSolicitante(arg){
            switch (conductorOSolictante) {
                case 'conductor':
                    $('#conductor').val($(arg).data('id'));
                    $('#conductor_info').val($(arg).data('user'));
                    $('#modal_large').modal('hide');
                    break;
            
                case 'solicitante':
                    $('#solicitante').val($(arg).data('id'));
                    $('#solicitante_info').val($(arg).data('user'));
                    $('#modal_large_solicitante').modal('hide');
                    break;
            }

            
        }

        function seleccionarVehiculo(arg){
            
            $('#vehiculo').val($(arg).data('id'));
            $('#numeroMovil').val($(arg).data('numeromovil'));
            $('#marca').val($(arg).data('marca'));
            $('#modelo').val($(arg).data('modelo'));
            $('#placa').val($(arg).data('placa'));
            $('#tipo').val($(arg).data('tipo'));
            $('#color').val($(arg).data('color'));
            $('#conductor').val($(arg).data('conductorid'));
            $('#conductor_info').val($(arg).data('conductorinfo'));
            $('#modal_large_vehiculo').modal('hide');
        }


        // fechas inicializacion
        const picker= new tempusDominus.TempusDominus(document.getElementById('datetimepicker1'),{
            display: {
                buttons:{
                    close:true,
                },
            },
            hooks:{
                inputFormat:(context, date) => { return moment(date).format('YYYY/MM/DD HH:mm') }
            }
        });
        const picker2= new tempusDominus.TempusDominus(document.getElementById('datetimepicker2'),{
            display: {
                buttons:{
                    close:true,
                },
            },
            hooks:{
                inputFormat:(context, date) => { return moment(date).format('YYYY/MM/DD HH:mm') }
            }
        });
    </script>
@endprepend
@endsection
