@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('home'))
@section('content')



  <div class="card card-body">
    <div id="map" style="height: 500px;"></div>    
  </div>




  @push('scriptsPie')


<script>


  let marker=[];
  let map;
  function initMap() {
   
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 8,
        center: { lat: -0.9017404846945926, lng: -78.88722629962153 },
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
          draggable: true,
          geodesic: true,
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
    }
    

    function quitarMarcadores(){
      for (let i = 0; i < marker.length; i++) {
        marker[i].setMap(null);
      }
      marker = [];
      
    }
    cargarCoordenadasParqueaderos();
    dibujarMarcadores();

}

window.initMap = initMap;

 
</script>



  <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDxUyVFlNpM-HwzkAokj9g1I1OOpS4kZI&callback=initMap&v=weekly"
      defer
  ></script>
  @endpush



@endsection
