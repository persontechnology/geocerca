@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('ingresoKilometraje.ingresar'))
@section('content')

<form action="{{ route('ingresoCombustible.guardar') }}" method="POST" id="formGuardarKilometraje" enctype="multipart/form-data">
    @csrf

    <div class="card">
        <div class="card-header">
        Comlete los siguentes datos.
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-sm-4">
                    <div class="form-group">
                        <label  for="codigo">Ingrese código de autorización<i class="text-danger">*</i></label>
                        <div class="input-group">
                            <input type="text" name="codigo" value="{{ old('codigo') }}" class="form-control form-control-lg @error('placa') is-invalid @enderror" id="codigo" autofocus required>
                            @error('codigo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="valor">Valor Dinero<i class="text-danger">*</i></label>
                        <input id="valor" type="text" class="form-control @error('valor') is-invalid @enderror" name="valor" value="{{ old('valor',$dc->valor??'') }}" required />
                        <p id="letras_valor_convertido"></p>
                        @error('valor')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input id="valor_letras" type="hidden" class="form-control @error('valor_letras') is-invalid @enderror" name="valor_letras" value="{{ old('valor_letras',$dc->valor_letras??'') }}" required />
                        @error('valor_letras')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="foto">Foto de evidencia<i class="text-danger">*</i></label>
                        <label class="custom-file">
                            <input type="file" accept="image/*" id="foto" name="foto"
                                class="custom-file-input @error('foto') is-invalid @enderror">
                            <span class="custom-file-label">Seleccione foto</span>
                        </label>
                        <span class="form-text text-muted">Formatos aceptados: gif, png, jpg, jpeg.</span>
                        @error('foto')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
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
    <p id="demo"></p>
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

<script src="{{ asset('js/numeroALetras.js') }}"></script>
<script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>


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

        $("#valor").maskMoney({prefix:'', allowNegative: true, thousands:'', decimal:'.', affixesStay: false});
        var vt=$('#valor').on('keyup keydown keypress',e=>{
            cargarValorLetras(e.target.value);
        })

        function cargarValorLetras(value){
            var res=numeroALetras(value, {
                plural: 'dólares',
                singular: 'dólar',
                centPlural: 'centavos',
                centSingular: 'centavo'
            });
            $('#valor_letras').val(res)
            $('#letras_valor_convertido').html(res)
        }

        cargarValorLetras($('#valor').val())

        $("#galones").maskMoney({prefix:'', allowNegative: true, thousands:'', decimal:'.', affixesStay: false});
        $('#galones').on('keyup keydown keypress',e=>{
            cargarGalonesLetras(e.target.value);
        })

        function cargarGalonesLetras(value){
            var res=numeroALetras(value, {
                plural: 'galones',
                singular: 'galon',
                centPlural: '.',
                centSingular: '.'
            });
            $('#cantidad_letras').val(res)
            $('#letras_cantidad_galones_convertido').html(res)
        }
        cargarGalonesLetras($('#galones').val())
            


        // gps
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else { 
                alert("La geolocalización no es compatible con este navegador.");
            }
        }

        function showPosition(position) {
            alert(position.coords.latitude )
        }

        function showError(error) {
            let msgerror=''
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    msgerror="El usuario denegó la solicitud de geolocalización. Porfavor active su ubicación.";
                break;
                case error.POSITION_UNAVAILABLE:
                    msgerror="La información de ubicación no está disponible.";
                break;
                case error.TIMEOUT:
                    msgerror="Se agotó el tiempo de espera de la solicitud para obtener la ubicación del usuario.";
                break;
                case error.UNKNOWN_ERROR:
                    msgerror="Un error desconocido ocurrió. en Ubicación.";
                break;
            }

            $.dialog({
                theme: 'Modern',
                type: 'blue',
                closeIcon: true,
                icon: 'fa-solid fa-triangle-exclamation fa-beat',
                title: 'Ubicación',
                content: msgerror,
            });
        }
        getLocation();
        
    </script>
@endprepend

@push('linksPie')

<script>
       

   
</script>
@endpush
@endsection
