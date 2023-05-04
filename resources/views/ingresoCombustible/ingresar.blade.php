@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('ingresoKilometraje.ingresar'))
@section('content')

@if ($dc->estado==='Autorizado')
    

    <form action="{{ route('ingresoCombustible.guardar') }}" method="POST" id="formGuardarKilometraje" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="latitude_txt" id="latitude_txt">
        <input type="hidden" name="longitude_txt" id="longitude_txt">
        <input type="hidden" name="id" value="{{ $dc->id }}">
        <div class="card">
            <div class="card-header">
            Comlete los siguentes datos.
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-sm-6">
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
                    
                    <div class="col-sm-6">
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

            </div>
            <div class="card-footer text-muted">
            <button type="submit" class="btn btn-primary btn-lg">Guardar</button>
            </div>
        </div>
    </form>


@endif

<div class="card">
    <div class="card-header">
        Formulario despacho de combustible {{ $dc->numero }}
    </div>
    <div class="card-body">
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="{{ route('ingresoCombustible.pdf',$dc->id) }}" allowfullscreen></iframe>
          </div>
    </div>
</div>



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
            content: 'Está seguro de ingresar despacho de combustible.!',
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



    // gps
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else { 
            $.dialog({
                theme: 'Modern',
                type: 'blue',
                closeIcon: true,
                icon: 'fa-solid fa-triangle-exclamation fa-beat',
                title: 'Ubicación',
                content: "La geolocalización no es compatible con este navegador.",
            });
        }
    }

    function showPosition(position) {
        $('#latitude_txt').val(position.coords.latitude);
        $('#longitude_txt').val(position.coords.longitude);
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

        $.alert({
            theme: 'Modern',
            type: 'blue',
            closeIcon: false,
            icon: 'fa-solid fa-triangle-exclamation fa-beat',
            title: 'Ubicación',
            content: msgerror,
            buttons: {
                ok: function () {
                    window.location.reload();
                }
            }
        });
    }
    getLocation();
    
</script>
@endprepend

@endsection
