@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('odernMovilizacionLecturas',$orden))

@section('barraLateral')
<div class="breadcrumb justify-content-center">
    <a href="{{ route('odernMovilizacionPdf',$orden->id) }}" target="_blank" class="breadcrumb-elements-item">
        <i class="fa-solid fa-file-pdf mr-1 text-info"></i>Descargar PDF
        
    </a>
</div>
@endsection
@section('content')

<div class="card card-body">
    
    @if ($orden->lecturas->count()>0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Fecha</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orden->lecturas as $lec)
                    <form action="{{ route('odernMovilizacionLecturaActualizar') }}" autocomplete="off" method="POST">
                        @csrf

                        <tr class="">
                            <td scope="row">
                                {{ $lec->created_at }}
                                <input type="hidden" name="id" value="{{ $lec->id }}">
                            </td>
                            <td>
                                <select name="estado" id="estado_{{ $lec->id }}" class="form-control">
                                    <option value="DENTRO" {{ $lec->estado==='DENTRO'?'selected':'' }}>DENTRO</option>
                                    <option value="FUERA" {{ $lec->estado==='FUERA'?'selected':'' }}>FUERA</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="descripcion" value="{{ $lec->descripcion }}" class="form-control">
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </td>
                        </tr>
                    </form>
                    @endforeach  
                    
                </tbody>
            </table>
        </div>
        
                  
    @else
        @include('layouts.alert',['type'=>'info','msg'=>'Orden de movilización no tiene lecturas.'])
    @endif

</div>
@push('scripts')
    
@endpush
@endsection
