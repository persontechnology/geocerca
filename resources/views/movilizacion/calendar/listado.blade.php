@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('odernMovilizacionListado'))

@section('barraLateral')
<div class="breadcrumb justify-content-center">
    <a href="{{ route('odernMovilizacion') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-car-side mr-1 text-info"></i>
        Nuevo orden de movilización
    </a>
    <a href="{{ route('odernMovilizacionReportePdf') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-file-pdf mr-1 text-primary"></i>
        Reporte PDF
    </a>

</div> 
@endsection
@section('content')

<div class="card">
    <div class="card-header">
        @include('movilizacion.estados')
    </div>
    <div class="card-body">
        <div class="table-responsive">
            {{$dataTable->table()}}
        </div>
    </div>
</div>
@push('scripts')
    {{$dataTable->scripts()}}
@endpush
@endsection
