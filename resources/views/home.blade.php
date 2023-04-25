@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('home'))
@section('content')



  <div class="card card-body">
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
@push('linksCabeza')
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>          
@endpush
@push('scripts')


<script>


  let marker=[];
  let map;
  function initMap() {
   
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 8,
      center: { lat: -0.9017404846945926, lng: -78.88722629962153 },
    });


    const input = document.getElementById("pac-input");
    const autocomplete = new google.maps.places.Autocomplete(input, {
        fields: ["place_id", "geometry", "formatted_address", "name"],
    });
    autocomplete.bindTo("bounds", map);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    const marker_place = new google.maps.Marker({ map: map });
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

      marker_place.setPlace({
          placeId: place.place_id,
          location: place.geometry.location,
      });
      marker_place.setVisible(true);

    });


    const cargarCoordenadasParqueaderos=async()=>{
      const response=await fetch("{{ route('coordenadasParqueaderos') }}");
      const myJson=await response.json();

      

      myJson.forEach((nombre,indice)=>{
        var triangleCoordenadas=[];  
        
        nombre[0].forEach( function(valor, indice, array) {
              triangleCoordenadas.push({lat:valor[1],lng: valor[0]})
        });

        new google.maps.Polygon({
          map,
          paths: triangleCoordenadas,
          strokeColor: "#FF00"+indice*2,
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: "#FF0000",
          fillOpacity: 0.35,
          draggable: false,
          geodesic: false,
        });
      });
    }

    const infoWindow = new google.maps.InfoWindow();
    
    var tiempo={{ ($empresa->tiempo_api_rest??1)*60000 }}
    setInterval(dibujarMarcadores,tiempo);
    
    async function dibujarMarcadores() {
      
      quitarMarcadores();
      const response = await fetch("{{ route('coordenadasAutosMapa') }}");
      const myJson = await response.json();
      

      myJson.forEach((data, i) => {
        
        position={lat: data[0][0], lng: data[0][1]};
        
        marker.push(
          new google.maps.Marker({
            position: position,
            map,
            // animation: google.maps.Animation.DROP,
            title: data[0][2],
            label: data[0][3],
            optimized: false,
          })
        );

        marker[i].addListener("click", () => {
          
          infoWindow.close();
          infoWindow.setContent(marker[i].getTitle());
          infoWindow.open(marker[i].getMap(), marker[i]);
        });
      });
      new markerClusterer.MarkerClusterer({ marker, map });
    }
    

    function quitarMarcadores(){
      for (let i = 0; i < marker.length; i++) {
        marker[i].setMap(null);
      }
      marker = [];
      
    }
    
    const markerCluster = new markerClusterer.MarkerClusterer({ marker,map });
    
    cargarCoordenadasParqueaderos();
    dibujarMarcadores();

  }

  window.initMap = initMap;

 
</script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDxUyVFlNpM-HwzkAokj9g1I1OOpS4kZI&callback=initMap&libraries=drawing,places&v=weekly" defer></script>
  

@endpush


@endsection
