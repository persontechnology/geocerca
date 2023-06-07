@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('usuarios'))

@section('barraLateral')
<div class="breadcrumb justify-content-center">
    <a href="{{ route('usuariosNuevo') }}" class="breadcrumb-elements-item">
        <i class="fas fa-plus"></i>
        Nuevo usuario
    </a>
    
<div class="breadcrumb-elements-item dropdown p-0">
    <a href="#" class="breadcrumb-elements-item dropdown-toggle" data-toggle="dropdown">
        <i class="fas fa-users"></i>
        Ver usuarios
    </a>

    <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ route('usuarios') }}" class="dropdown-item"><i class="fas fa-user-lock"></i>Todos</a>
        <a href="{{ route('usuariosPoRol','INACTIVOS') }}" class="dropdown-item"><i class="fa-solid fa-toggle-on"></i>Inactivos</a>
        @foreach ($roles as $rol)
            <a href="{{ route('usuariosPoRol',$rol->name) }}" class="dropdown-item"><i class="fas fa-user-lock"></i>{{ $rol->name }}</a>
        @endforeach
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
