@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('odernMovilizacionMultiple'))

@section('content')
<form action="{{ route('odernMovilizacionMultipleGuardar') }}" id="formOrdenMovilizacion" method="POST" autocomplete="off">
    @csrf
    <div class="card">
        <div class="card-header">
            <strong>Se creará OM para el SÁBADO y DOMINGO</strong>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="fecha_solicitud">Fecha de solicitud:</label>
                        <input type="text" id="fecha_solicitud" name="fecha_solicitud" readonly onkeydown="event.preventDefault()" value="{{ old('fecha:solictud',Carbon\Carbon::now()) }}" class="form-control">
                    </div>
                </div>

                <div class="col-lg-4">


                    <div class="form-group">
                        <label for="fecha_salida">Fecha y hora de salida<i class="text-danger">*</i></label>
                            
                        <div class='input-group' id='datetimepicker1' data-td-target-input='nearest' data-td-target-toggle='nearest'>
                            
                            <input id='fecha_salida' onkeydown="event.preventDefault()" name="fecha_salida" type='text' class="form-control @error('fecha_salida') is-invalid @enderror" value="{{ old('fecha_salida',$proximo_sabado)}}" data-td-target='#datetimepicker1' required/>
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
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="fecha_retorno">Fecha y hora de retorno<i class="text-danger">*</i></label>
                            
                        <div class='input-group' id='datetimepicker2' data-td-target-input='nearest' data-td-target-toggle='nearest'>
                            <input id='fecha_retorno' onkeydown="event.preventDefault()" name="fecha_retorno" type='text' class="form-control @error('fecha_retorno') is-invalid @enderror" value="{{ old('fecha_retorno',$proximo_domingo)}}" data-td-target='#datetimepicker2' required/>
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

                

                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="solicitante_info">Datos del solicitante / <small>solo el usuario Supervisor puede cambiar de solicitante</small></label>
                        <div class="input-group">
                            <input type="hidden" name="solicitante" id="solicitante" value="{{ old('solicitante',Auth::user()->id) }}">
                            <input type="text" data-opcion="solicitante" onclick="modalConductorSolicitante(this);" onkeydown="event.preventDefault()" readonly id="solicitante_info" name="solicitante_info" value="{{ old('solicitante_info',Auth::user()->apellidos_nombres??'') }}"  data-toggle="modal" data-target="#{{ Auth::user()->hasRole('Supervisor')?'modal_large_s':'na' }}" class="form-control @error('solicitante') is-invalid @enderror" placeholder="Seleccionar solicitante..">
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

                <div class="col-lg-6" id="aceptar_orden_movilizacion">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" value="ACEPTADA" {{ old('estado')=='ACEPTADA'?'checked':'' }} name="estado" id="estado">
                        <label class="form-check-label" for="estado">
                            ACEPTAR ORDENES DE MOVILIZACIÓN
                        </label>
                    </div>
                </div>


            </div>

            <h5 class="card-title">Selecionar veículos</h5>
            <select multiple="multiple" class="form-control listbox" name="vehiculos[]" data-fouc>
                @foreach ($vehiculos as $veh)
                <option value="{{ $veh->id }}">{{ $veh->numero_movil }} {{ $veh->placa }}</option>
                @endforeach
            </select>
        </div>
        <div class="card-footer" id="contenedorGuardar">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>

</form>

<div id="modal_large_s" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalConductorSolicitante_s">Selecionar solicitante</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    {{$dataTable->table()}}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="buttonModalConductorSolicitante_s" onclick="seleccionarConductorSolicitante(this);" data-id="" data-user="" class="btn btn-primary" data-dismiss="modal">Sin</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection




@push('linksCabeza')

 <!-- Popperjs -->
 <script src="{{ asset('js/popper.min.js') }}"></script>
 <!-- Tempus Dominus JavaScript -->
 <script src="{{ asset('js/tempus-dominus/dist/js/tempus-dominus.js') }}"></script>

 <!-- Tempus Dominus Styles -->
 <link rel="stylesheet" href="{{ asset('js/tempus-dominus/dist/css/tempus-dominus.css') }}">
 <script src="{{ asset('js/monent.js') }}"></script>

 <script src="{{ asset('js/jquery.blockUI.js') }}"></script>
 <script src="{{ asset('js/validate/jquery.validate.min.js') }}"></script>
 <script src="{{ asset('js/validate/messages_es.min.js') }}"></script>


<script src="{{ asset('global_assets/js/plugins/forms/inputs/duallistbox/duallistbox.min.js') }}"></script>


<script>
    $.blockUI.defaults.message = `<div class="d-flex align-items-center">
                                    <strong class="mx-2">Procesando...</strong>
                                    <div class="spinner-grow spinner-grow-sm ms-auto mx-2" role="status" aria-hidden="true"></div>
                                </div>`; 

    $.validator.setDefaults( {
        
        errorElement: "strong",
        errorPlacement: function ( error, element ) {
            // Add the `invalid-feedback` class to the error element
            error.addClass( "invalid-feedback" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.next( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    } );

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@endpush




@prepend('linksPie')
<script>
    $('.listbox').bootstrapDualListbox({
        moveOnSelect: true,
        infoText: 'Mostrar todo {0}',
        infoTextFiltered: '<span class="badge bg-warning-400">Filtrado</span> {0} de {1}',
        infoTextEmpty: 'Lista vacía',
        filterPlaceHolder: 'Buscar..',
        filterTextClear: 'Mostrar todo'
    });


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


    $('#formOrdenMovilizacion').validate({
        submitHandler: function(form) {
                $('#contenedorGuardar').block(); 
                form.submit();
            },
    });


</script>
@endprepend


@push('scripts')
    {{$dataTable->scripts()}}

    <script>
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
                    $('#tituloModalConductorSolicitante_s').html('Selecionar solicitante');
                    $('#buttonModalConductorSolicitante_s').html('Sin solicitante');
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
                    $('#modal_large_s').modal('hide');
                    break;
            }

            
            
        }
    </script>
@endpush
