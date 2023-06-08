@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('parqueaderosEditar', $parqueadero))
@section('content')

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
                <div style="display: none">
                    <input
                      id="pac-input"
                      class="controls form-control form-control-sm"
                      type="search"
                      placeholder="Buscar lugar..."
                    />
                  </div>
                  <div id="map"></div>
            </div>
            <div class="card-footer bg-transparent">
                <button type="submit" class="btn btn-primary mt-3 mt-sm-0 w-100 w-sm-auto">Actualizar</button>
                <a class="btn btn-danger" href="{{ route('parqueaderos') }}">Cancelar</a>
            </div>
        </div>

    </form>
    @push('linksCabeza')
        <style>
            /* 
            * Always set the map height explicitly to define the size of the div element
            * that contains the map. 
            */
            #map {
                height: 750px;
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

            .controls {
                background-color: #fff;
                border-radius: 2px;
                border: 1px solid transparent;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                box-sizing: border-box;
                font-family: Roboto;
                font-size: 15px;
                font-weight: 300;
                height: 29px;
                margin-left: 5px;
                margin-top: 4px;
                outline: none;
                padding: 0 11px 0 13px;
                text-overflow: ellipsis;
                width: 300px;
            }

            .controls:focus {
                border-color: #4d90fe;
            }

            .title {
                font-weight: bold;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // This example requires the Drawing library. Include the libraries=drawing
            // parameter when you first load the API. For example:
            // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">

            let drawingManager;
            let map;
            let coordenadas = {{ json_encode($area) }};
            let triangleCoords = [];

            var bounds;
            function initMap() {

                bounds= new google.maps.LatLngBounds()

                if(coordenadas.length>0){
                    coordenadas[0].forEach( function(valor, indice, array) {
                        triangleCoords.push({lat:valor[1],lng: valor[0]})
                        bounds.extend(triangleCoords[indice]);
                    });
                }


                map = new google.maps.Map(document.getElementById("map"), {
                    center: bounds.getCenter(),
                    zoom: 15,
                });
                
                const input = document.getElementById("pac-input");
                // Specify just the place data fields that you need.
                const autocomplete = new google.maps.places.Autocomplete(input, {
                    fields: ["place_id", "geometry", "formatted_address", "name"],
                });

                autocomplete.bindTo("bounds", map);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            

                const marker = new google.maps.Marker({ map: map });

            
                autocomplete.addListener("place_changed", () => {
                

                    const place = autocomplete.getPlace();

                    if (!place.geometry || !place.geometry.location) {
                    return;
                    }

                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }

                    // Set the position of the marker using the place ID and location.
                    // @ts-ignore This should be in @typings/googlemaps.
                    marker.setPlace({
                        placeId: place.place_id,
                        location: place.geometry.location,
                    });
                    marker.setVisible(true);

                });

                // Construct the polygon.
                drawingManager = new google.maps.Polygon({
                    paths: triangleCoords,
                    strokeColor: "#FF0000",
                    strokeOpacity: 0.8,
                    strokeWeight: 1,
                    fillColor: "#FF0000",
                    fillOpacity: 0.35,
                });

                drawingManager.setMap(map);
                
                drawingManager = new google.maps.drawing.DrawingManager({
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
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDxUyVFlNpM-HwzkAokj9g1I1OOpS4kZI&callback=initMap&libraries=drawing,places&v=weekly"
        defer
        ></script>
    @endpush
@endsection
