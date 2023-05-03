@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('ingresoKilometraje.ingresar'))
@section('content')

<form action="{{ route('ingresoKilometraje.guardar') }}" method="POST" id="formGuardarKilometraje">
    @csrf

    <div class="card">
        <div class="card-header">
        Comlete los siguentes datos.
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="border p-3 rounded">
                        <div class="d-flex align-items-center">
                            <input onchange="cambiarKIlometrajeSiNo(this);" type="radio" name="ingreso" id="kilometraje_si" value="SI" {{ old('ingreso')==='SI'?'checked':'' }} checked>
                            <label class="ml-2" for="kilometraje_si"> Ingreso con kilometraje</label>
                        </div>

                        <div class="d-flex align-items-center mb-2">
                            <input onchange="cambiarKIlometrajeSiNo(this);" type="radio" name="ingreso" id="kilometraje_no" value="NO" {{ old('ingreso')==='NO'?'checked':'' }}>
                            <label class="ml-2" for="kilometraje_no">Ingreso sin kilometraje</label>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label  for="kilometraje">Ingrese nuevo kilometraje<i class="text-danger">*</i></label>
                        <div class="input-group">
                            <input type="number" name="kilometraje" value="{{ old('kilometraje') }}" class="form-control form-control-lg @error('placa') is-invalid @enderror" id="kilometraje" autofocus required>
                            @error('kilometraje')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">

                    @if ($parqueaderos->count()>0)
                        <div class="form-group">
                            <label for="parqueadero">Parqueadero<i class="text-danger">*</i></label>
                            <select name="parqueadero" id="parqueadero" class="form-control @error('parqueadero') is-invalid @enderror" required>
                                @foreach ($parqueaderos as $par)
                                <option value="{{ $par->id }}" {{ old('parqueadero') == $par->id ? 'selected' : '' }}>{{ $par->nombre }}</option>    
                                @endforeach
                            </select>

                            @error('parqueadero')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @else
                    @include('layouts.alert',['type'=>'info','msg'=>'Usuario guardia no tiene asignado parqueadero.! por el cual no puede ingresar kilometraje.'])
                    @endif
                    
                </div>

                
            </div>

            <button type="button" class="btn btn-block btn-outline-dark my-2" data-toggle="modal" data-target="#modal_full">Seleccionar vehículo <i class="fa-solid fa-car ms-2"></i></i></button>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label  for="numeroMovil">N° Movil</label>
                        <div class="input-group">
                            
                            <input type="hidden" name="vehiculo" id="vehiculo" value="{{ old('vehiculo') }}" required>
                            <input type="text" readonly onkeydown="event.preventDefault()" name="numeroMovil" value="{{ old('numeroMovil') }}" class="form-control @error('vehiculo') is-invalid @enderror" id="numeroMovil" placeholder="Vehículo sin selecionar.!" required>
                            
                            @error('vehiculo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label  for="marca">Marca</label>
                        <div class="input-group">
                            <input type="text" readonly onkeydown="event.preventDefault()" name="marca" value="{{ old('marca') }}" class="form-control" id="marca">
                            @error('marca')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label  for="modelo">Modelo</label>
                        <div class="input-group">
                            <input type="text" readonly onkeydown="event.preventDefault()" name="modelo" value="{{ old('modelo') }}" class="form-control @error('modelo') is-invalid @enderror" id="modelo">
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
                <div class="col-sm-4">
                    <div class="form-group">
                        <label  for="placa">Placa</label>
                        <div class="input-group">
                            <input type="text" readonly onkeydown="event.preventDefault()" name="placa" value="{{ old('placa') }}" class="form-control @error('placa') is-invalid @enderror" id="placa">
                            @error('placa')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label  for="tipo">Tipo</label>
                        <div class="input-group">
                            <input type="text" readonly onkeydown="event.preventDefault()" name="tipo" value="{{ old('tipo') }}" class="form-control" id="tipo">
                            @error('tipo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label  for="color">Color</label>
                        <div class="input-group">
                            <input type="text" readonly onkeydown="event.preventDefault()" name="color" value="{{ old('color') }}" class="form-control @error('color') is-invalid @enderror" id="color">
                            @error('color')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="ultimoKilometraje">Último kilometraje</label>
                        <div class="input-group">
                            <input type="text" onkeydown="event.preventDefault()" readonly id="ultimoKilometraje" name="ultimoKilometraje" value="{{ old('ultimoKilometraje') }}" class="form-control @error('ultimoKilometraje') is-invalid @enderror">
                        </div>

                        @error('ultimoKilometraje')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="conductor_info">Datos del conductor</label>
                        <div class="input-group">
                            <input type="hidden" name="conductor" id="conductor" value="{{ old('conductor') }}">
                            <input type="text" data-opcion="conductor" onclick="modalConductorSolicitante(this);" onkeydown="event.preventDefault()" readonly id="conductor_info" name="conductor_info" value="{{ old('conductor_info') }}" data-toggle="modal" data-target="#modal_large" class="form-control @error('conductor') is-invalid @enderror" placeholder="">
                        
                        </div>

                        @error('conductor')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
        <button type="submit" class="btn btn-primary btn-lg">Guardar</button>
        </div>
    </div>

</form>

    <!-- Full width modal -->
    <div id="modal_full" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Listado de vehiculos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive"> 
                        {{$dataTable->table()}}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /full width modal -->




@push('scripts')
    {{$dataTable->scripts()}}
    <script>
        function selecionarVehiculo(arg){
            
            $('#vehiculo').val($(arg).data('id'));
            $('#numeroMovil').val($(arg).data('numeromovil'));
            $('#marca').val($(arg).data('marca'));
            $('#modelo').val($(arg).data('modelo'));
            $('#placa').val($(arg).data('placa'));
            $('#tipo').val($(arg).data('tipo'));
            $('#color').val($(arg).data('color'));
            $('#conductor').val($(arg).data('conductorid'));
            $('#conductor_info').val($(arg).data('conductorinfo'));
            $('#ultimoKilometraje').val($(arg).data('ultimokilometraje'))
            $('#modal_full').modal('hide');
        }

        function cambiarKIlometrajeSiNo(arg){
            if($(arg).val()==='NO'){
                $('#kilometraje').val('');
                $('#kilometraje').prop('readonly', true);
            }else{
                $('#kilometraje').prop('readonly', false);
            }
        }


        

    </script>
@endpush
@prepend('linksPie')
    <script>
        $('#formGuardarKilometraje').submit(function(event) {
            event.preventDefault();
            var form = $(this)[0];

            $.confirm({
                theme: 'Modern',
                type: 'blue',
                closeIcon: true,
                icon: 'fa-solid fa-triangle-exclamation fa-beat',
                title: 'Confirmar!',
                content: 'Está seguro de ingresar nuevo kilometraje al vehículo '+$('#numeroMovil').val(),
                buttons: {
                    confirmar: function() {
                        form.submit();
                    },
                    cancelar: function() {

                    }
                }
            });

        });
    </script>
@endprepend
@endsection
