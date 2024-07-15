@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('direcciones-departamentos.index'))


@section('barraLateral')
<div class="breadcrumb justify-content-center">
    <a href="{{ route('direcciones-departamentos.create') }}" class="breadcrumb-elements-item">
        <i class="fa-solid fa-table-cells-large mr-1 text-info"></i>
        Nuevo departamento
    </a>
</div> 
@endsection

@section('content')

<div class="accordion" id="accordionExample">
    
   
    <div class="card">
      <div class="card-header" id="headingThree">
        <h2 class="mb-0">
          <button class="btn btn-ligth btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Direcciones
          </button>
        </h2>
      </div>
      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
        <div class="card-body">
            <form action="{{ route('direcciones-departamentos.store') }}" class="mx-1" autocomplete="off" method="POST">
                @csrf
    
                <label for="nombre">Nombre de dirección:</label>
    
                <div class="input-group">
                    <input id="nombre" placeholder="Ingresar nuevo dirección" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
                @error('nombre')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                
            </form>
        </div>
        <div class="card-footer">
            @foreach ($departamentos as $tv)
                <a href="#" onclick="event.preventDefault();eliminar(this);" data-id="{{ $tv->id }}" data-url="{{ route('departamentos.eliminar') }}" data-msg="Está seguro de eliminar {{ $tv->nombre }}!" class="dropdown-item"><i class="fa-solid fa-trash text-danger"></i> {{ $tv->nombre }}</a>
            @endforeach
        </div>
      </div>
    </div>
  </div>




<div class="card card-body">
    <div class="table-responsive">
        {{$dataTable->table()}}
    </div>
</div>
@push('scripts')
    {{$dataTable->scripts()}}
@endpush
@endsection
