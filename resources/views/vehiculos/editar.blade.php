@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('vehiculosEditar',$vehiculo))


@section('content')

<form action="{{ route('actualizarVehiculo') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
<div class="card">
    <div class="card-body">
        @csrf
        <input type="hidden" name="id" value="{{ $vehiculo->id }}" required>
        <div class="row">
            <div class="col-lg-10">
                <legend class="font-weight-semibold"><i class="fa-solid fa-address-card"></i> Detalle de vehículo</legend>

                <div class="row">
                    <div class="col-lg-12">
                    
                        @if ($departamentos->count()>0)
            
                            <div class="form-group">
                                <label for="departamento">Seleccione departamento</label>
                                <select name="departamento" id="departamento" class="form-control @error('departamento') is-invalid @enderror">
                                    @foreach ($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}" {{ old('departamento',$vehiculo->direccion->departamento_id??'')==$departamento->id?'selected':'' }}>{{ $departamento->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('departamento')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="direccion">Seleccione dirección</label>
                                <select name="direccion" id="direccion" class="form-control">
                                    <option value="" selected >------</option>
                                </select>
                            </div>

                            
                        @endif
                        
                    </div>
                </div>


                @include('vehiculos.datos',['vehiculo'=>$vehiculo])
                <div class="form-group">
                    <label for="kilometraje">Kilometraje<i class="text-danger">*</i></label>
                    <input id="kilometraje" type="number" class="form-control @error('kilometraje') is-invalid @enderror" name="kilometraje" value="{{ old('kilometraje', $kilometraje) }}" required />
                    @error('kilometraje')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-lg-2">
                <legend class="font-weight-semibold"><i class="fa-solid fa-truck"></i> Tipo de vehículo</legend>
                <fieldset>
                    @foreach ($tipoVehiculos as $tv)
                    <div class="form-check">
                        <input type="radio" value="{{$tv->id}}" {{ $vehiculo->tipo_vehiculo_id==$tv->id ?'checked':'' }} name="tipoVehiculo"  class="form-check-input" id="permi-0-{{ $tv->id }}">
                        <label class="form-check-label" for="permi-0-{{ $tv->id }}" >{{ $tv->nombre }}
                        </label>
                    </div>
                    
                @endforeach
                    
                </fieldset>
            </div>



        </div>
    </div>
    <div class="card-footer bg-transparent">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a class="btn btn-danger" href="{{ route('vehiculos') }}">Cancelar</a>
    </div>
</div>
</form>


 <!-- Full width modal -->
 <div id="modal_full" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selecionar conductor</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    {{$dataTable->table()}}
                </div>
            </div>

            <div class="modal-footer">
                
                <button type="button" onclick="seleccionarConductor(this);" data-id="" data-user="" class="btn btn-primary" data-dismiss="modal">Sin conductor</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /full width modal -->
@push('scripts')
    {{$dataTable->scripts()}}
@endpush



@prepend('linksPie')
    <script>
        function seleccionarConductor(arg){
            var id=$(arg).data('id');
            var user=$(arg).data('user');
            $('#conductor').val(id);
            $('#conductorInfo').val(user);
            $('#modal_full').modal('hide');
        }

        // cargar direcciones

        var direccionId=parseInt("{{ $vehiculo->direccion_id }}");
        var departamentoIdExiste=parseInt("{{ $vehiculo->direccion->departamento_id??0 }}");
        
        var departamentoId =departamentoId??$('#departamento').val();
        obtenerListadoDirecciones(departamentoId);

        // Obtener el valor seleccionado cuando cambie la selección
        $('#departamento').change(function() {
            var departamentoId = $(this).val();
            obtenerListadoDirecciones(departamentoId)
        });

        function obtenerListadoDirecciones(departamentoId){
            $.ajax({
                url: '/obtener-direcciones-x-departamento/' + departamentoId,
                type: 'GET',
                success: function(data) {
                    var $direccion = $('#direccion');
                    $direccion.empty(); // Vaciar el select de direcciones
                    
                    $direccion.append('<option value="" selected>------</option>'); // Opción por defecto
                    
                    $.each(data, function(index, direccion) {
                        if(direccion.id==direccionId){
                            $direccion.append('<option value="' + direccion.id + '" selected>' + direccion.nombre + '</option>');    
                        }else{
                            $direccion.append('<option value="' + direccion.id + '">' + direccion.nombre + '</option>');
                        }
                        
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
        }


    </script>
@endprepend

@endsection
