@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('estacion.create'))
@section('content')
<form action="{{ route('estacion.store') }}" method="POST">
    @csrf
    <div class="card">

        <div class="card-body">
            <fieldset>

                <div class="form-group">
                    <label for="nombre">Nombre<i class="text-danger">*</i></label>
                    <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required autofocus>

                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            <input type="hidden" id="area" name="area">
            </fieldset>
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
            <button class="btn btn-primary">Guardar</button>
            <a class="btn btn-danger" href="{{ route('estacion.index') }}">Cancelar</a>
        </div>
    </div>
</form>

<form id="form-estacion-eliminar" action="" method="POST" class="d-none">
    @csrf
    @method('delete')
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
      
        let drawingManager;
        let map;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 8,
                center: { lat: -0.9017404846945926, lng: -78.88722629962153 },
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


            drawingManager = new google.maps.drawing.DrawingManager({
                // drawingMode: google.maps.drawing.OverlayType.POLYGON,
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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDxUyVFlNpM-HwzkAokj9g1I1OOpS4kZI&callback=initMap&libraries=drawing,places&v=weekly" defer></script>
@endpush


@endsection
