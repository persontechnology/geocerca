@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('departamentos.index'))


@section('barraLateral')
<div class="breadcrumb justify-content-center">
    <a href="{{ route('direcciones.index') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-location-dot mr-1 text-primary"></i>
        Direcciones
    </a>

    <a href="{{ route('departamentos.create') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-table-cells-large mr-1 text-info"></i>
        Nuevo departamento
    </a>
</div> 
@endsection

@section('content')





<div class="card card-body">
    <div class="table-responsive">
        {{$dataTable->table()}}
    </div>
</div>
@push('scripts')
    {{$dataTable->scripts()}}
@endpush
@endsection
