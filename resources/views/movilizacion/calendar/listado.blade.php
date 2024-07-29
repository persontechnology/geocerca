@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('odernMovilizacionListado'))

@section('barraLateral')
<div class="breadcrumb justify-content-center">
    <a href="{{ route('odernMovilizacionMultiple') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-plus mr-1 text-warning"></i>
        Multiple
    </a>

    <a href="{{ route('odernMovilizacion') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-car-side mr-1 text-info"></i>
        Nuevo OM
    </a>
    <a href="{{ route('odernMovilizacionReportePdf') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-file-pdf mr-1 text-primary"></i>
        Reporte PDF
    </a>

</div> 
@endsection
@section('content')

<form action="{{ route('odernMovilizacionMultipleEliminar') }}" method="POST" id="odernMovilizacionMultipleEliminar">
    @csrf
    <div class="card">
        <div class="card-header">
            @include('movilizacion.estados')

            <button type="button" onclick="eliminarMultipleOm(this);" class="btn btn-danger d-none" id="btnEliminarOm">
                Eliminar ordenes de movilización selecionados
            </button>
            
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{$dataTable->table()}}
            </div>
        </div>
    </div>
</form>

@push('scripts')
    {{$dataTable->scripts()}}

    <script>


        function eliminarOm(checkbox) {
        // Obtener todos los checkboxes con la clase 'item-om-table'
        const checkboxes = document.querySelectorAll('.item-om-table');

        // Verificar si la casilla maestra está marcada o desmarcada
        if (checkbox.checked) {
            checkboxes.forEach(cb => cb.checked = true);
        } else {
            checkboxes.forEach(cb => cb.checked = false);
        }

        // Actualizar la visibilidad del botón según si hay checkboxes seleccionados
        actualizarBotonEliminar();
    }

    document.addEventListener('change', function(event) {
        if (event.target.classList.contains('item-om-table')) {
            actualizarBotonEliminar();
        }
    });

    function actualizarBotonEliminar() {
        const checkboxes = document.querySelectorAll('.item-om-table');
        const botonEliminar = document.getElementById('btnEliminarOm');
        
        // Verificar si hay al menos un checkbox marcado
        const haySeleccionado = Array.from(checkboxes).some(cb => cb.checked);
        
        // Mostrar u ocultar el botón según la selección
        if (haySeleccionado) {
            botonEliminar.classList.remove('d-none');
        } else {
            botonEliminar.classList.add('d-none');
        }
    }

    function eliminarMultipleOm(arg){


        $.confirm({
            theme: 'Modern',
            type: 'red',
            closeIcon: true,
            icon: 'fa-regular fa-face-sad-tear fa-beat',
            title: 'Confirmar!',
            content: "Está seguro de eliminar ordenes de movilizaciones seleccionados.!",
            buttons: {
                confirmar: function() {
                    $('#odernMovilizacionMultipleEliminar').submit();
                },
                cancelar: function() {

                }
            }
        });
    }

    </script>
@endpush
@endsection
