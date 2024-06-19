@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('direcciones-departamentos.index'))


@section('barraLateral')
<div class="breadcrumb justify-content-center">
    <a href="{{ route('direcciones-departamentos.create') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-table-cells-large mr-1 text-info"></i>
        Nuevo dirección
    </a>
    

    <div class="breadcrumb-elements-item dropdown p-0">
        <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
            <i class="fa-solid fa-table mr-1 text-indigo"></i>
            Departamentos
        </a>

        <div class="dropdown-menu dropdown-menu-right mr-2">
            <form action="{{ route('direcciones-departamentos.store') }}" class="mx-1" autocomplete="off" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input id="nombre" placeholder="Ingresar nuevo tipo" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required  autofocus>
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <div class="dropdown-divider"></div>
				@foreach ($departamentos as $tv)
                    <a href="#" onclick="event.preventDefault();eliminar(this);" data-id="{{ $tv->id }}" data-url="{{ route('departamentos.eliminar') }}" data-msg="Está seguro de eliminar {{ $tv->nombre }}!" class="dropdown-item"><i class="fa-solid fa-trash text-danger"></i> {{ $tv->nombre }}</a>
                @endforeach
            </form>

        </div>
    </div>
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
