@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('parqueaderosNuevo'))
@section('content')


    <form action="{{ route('parqueaderosGuardar') }}" method="POST" autocomplete="off">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('parqueaderos.datos',['parqueadero'=>null])
                    <div class="col-sm-4">
                        @if ($guardias->count() > 0)
                            <p>Guardias<i class="text-danger">*</i></p>
                            @foreach ($guardias as $guardia)
                                <div class="form-check">
                                    <input type="checkbox" value="{{ $guardia->id }}" {{ old('guardias.'.$guardia->id)==$guardia->id ?'checked':'' }} name="guardias[{{ $guardia->id }}]"  class="form-check-input @error('guardias.'.$guardia->id) is-invalid @enderror" id="guardia-{{ $guardia->id }}">
                                    <label class="form-check-label" for="guardia-{{ $guardia->id }}">{{ $guardia->apellidos_nombres }}</label>
                                </div>
                                
                            @endforeach
                        @else
                            @include('layouts.alert', [
                                'type' => 'info',
                                'msg' => 'No existe usuarios con rol guardia.!',
                            ])
                            <a href="{{ route('usuariosNuevo') }}">Crear uno nuevo</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted">
                <div id="map" style="height: 500px;"></div>
            </div>
            <div class="card-footer bg-transparent">
                <button type="submit" class="btn btn-primary mt-3 mt-sm-0 w-100 w-sm-auto">Guardar</button>
            </div>
        </div>
    </form>

@push('scripts')
    <script>
      
        let drawingManager;
        let map;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -1.0433796366054373, lng: -78.5907589882176 },
                zoom: 20,
            });
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        google.maps.drawing.OverlayType.POLYGON,
                    ],
                },
                polygonOptions: {
                    fillColor: "#ffff00",
                    fillOpacity: 1,
                    strokeWeight: 5,
                    editable:true
                },
            });

            drawingManager.setMap(map);
            
          

            // Add a listener for the "drag" event.
            google.maps.event.addListener(drawingManager, "overlaycomplete", function(event){
                overlayDragListener(event.overlay);
                $('#area').val(event.overlay.getPath().getArray());
            });
            function overlayDragListener(overlay) {
                google.maps.event.addListener(overlay.getPath(), 'set_at', function(event){
                    $('#area').val(overlay.getPath().getArray());
                });
                google.maps.event.addListener(overlay.getPath(), 'insert_at', function(event){
                    $('#area').val(overlay.getPath().getArray());
                });
            }
        }


        window.initMap = initMap;
    </script>

    <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDxUyVFlNpM-HwzkAokj9g1I1OOpS4kZI&callback=initMap&libraries=drawing&v=weekly"
    defer
    ></script>
@endpush


@endsection
