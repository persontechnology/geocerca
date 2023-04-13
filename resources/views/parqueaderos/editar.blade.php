@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('parqueaderosEditar', $parqueadero))
@section('content')
<style>
    /* 
    * Always set the map height explicitly to define the size of the div element
    * that contains the map. 
    */
    #map {
    height: 500px;
    }

    /* 
    * Optional: Makes the sample page fill the window. 
    */
    html,
    body {
    height: 100%;
    margin: 0;
    padding: 0;
    }
</style>

    <form action="{{ route('parqueaderosActualizar') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $parqueadero->id }}">
        <div class="card">

            <div class="card-body">
                <div class="row">
                    @include('parqueaderos.datos',['parqueadero'=>$parqueadero])
                    <div class="col-sm-4">
                        <p>Guardias<i class="text-danger">*</i></p>
                        @if ($guardias->count() > 0)
                            @foreach ($guardias as $guardia    )
                                <div class="form-check">
                                    <input type="checkbox" value="{{ $guardia->id }}" {{ $parqueadero->hasGuardia($guardia->id)?'checked':'' }}  name="guardias[{{ $guardia->id }}]"  class="form-check-input @error('guardias.'.$guardia->id) is-invalid @enderror" id="guardia-{{ $guardia->id }}">
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
                <div id="map"></div>
            </div>
            <div class="card-footer bg-transparent">
                <button type="submit" class="btn btn-primary mt-3 mt-sm-0 w-100 w-sm-auto">Actualizar</button>
            </div>
        </div>

    </form>
    @push('scripts')
    <script>
        // This example requires the Drawing library. Include the libraries=drawing
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">

        let drawingManager;
        let map;
        var coordenadas = <?php echo json_encode($area); ?>;
        
        


        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -1.0433796366054373, lng: -78.5907589882176 },
                zoom: 20,
            });

            const triangleCoords = [];

            coordenadas[0].forEach( function(valor, indice, array) {
                triangleCoords.push({lat:valor[1],lng: valor[0]})
            });

            console.log(triangleCoords)

            
            // Construct the polygon.
            const bermudaTriangle = new google.maps.Polygon({
                paths: triangleCoords,
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 3,
                fillColor: "#FF0000",
                fillOpacity: 0.35,
            });

            bermudaTriangle.setMap(map);



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
                },
            });

            drawingManager.setMap(map);

            google.maps.event.addListener(drawingManager, 'polygoncomplete', function(arg) {
                // console.log(arg.getPath().getArray())
                $('#area').val(arg.getPath().getArray())
                
            });

          

        }


        window.initMap = initMap;
    </script>

    <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDxUyVFlNpM-HwzkAokj9g1I1OOpS4kZI&callback=initMap&libraries=drawing&v=weekly"
    defer
    ></script>
@endpush
@endsection
