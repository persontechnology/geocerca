@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('controlOdernMovilizacion'))

@section('barraLateral')
<div class="breadcrumb justify-content-center">
    
    <a class="breadcrumb-elements-item" data-toggle="modal" href="#exampleModalToggle" role="button">
        ACEPTAR O.M.<i class="fa-solid fa-check ml-1"></i>
    </a>

</div>

@endsection
@section('content')

    @livewire('orden-movilizacion-control.listado')

    <!-- Modal APROBAR OM -->
    <form action="{{ route('controlOdernMovilizacionAprobarLista') }}" method="POST" id="formOrdenMovilizacion">
        @csrf
        <div class="modal fade" id="exampleModalToggle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-full modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Selecionar O.M, para aceptar.</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="container-fluid">
                    <div class="form-group">

                 
                        <label class="form-check-label" for="defaultCheck1supe">
                            Enviar órdenes de movilización a (separar correos con comas) a Supervisores:
                        </label>
                        <input type="text" name="correos"  value="{{ $emailsSupervisor }}" class="form-control" placeholder="Ejm: admin@gmail.com,secre@gmail.com">                           
                        


                    </div>
                </div>
                
                <div class="modal-body">
                    <div class="table-responsive">
                        {{$dataTable->table()}}
                    </div>
                </div>
                <div class="modal-footer" id="contenedorGuardar">
                    <button type="submit" class="btn btn-primary">Aceptar O.M</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
            </div>
        </div>
    </form>
    

    @push('linksCabeza')
    <script src="{{ asset('js/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('js/validate/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validate/messages_es.min.js') }}"></script>   

    <script>
        $.blockUI.defaults.message = `<div class="d-flex align-items-center">
										<strong class="mx-2">Aceptando OM & enviando email's...</strong>
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


@push('scripts')
{{$dataTable->scripts()}}
@endpush

@prepend('linksPie')

<script>
    $('#formOrdenMovilizacion').validate({
        submitHandler: function(form) {
                $('#contenedorGuardar').block(); 
                form.submit();
            },
    });

    @if ($errors->any())
    $('#exampleModalToggle').modal('show');    
    @endif
    

    function selecionarTodosLosCheckbox() {
        
        // Obtén el estado del checkbox principal
        let isChecked = $('#defaultCheck1ccc').is(':checked');
        // Selecciona o deselecciona todos los checkboxes con clase `form-check-input` dentro de tbody
        $('#ordenmovilizacion-listadoordenmovilizacion-table-aprobar tbody .form-check-input').prop('checked', isChecked);
    }

</script>
@endprepend

@endsection
