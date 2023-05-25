@extends('layouts.app')
@section('breadcrumbs', Breadcrumbs::render('vehiculosUbicacionMapa',$vehiculo))

@section('content')
    <div class="card" id="draggable-default-container">
        <div class="card-body">
            <div id="map"></div>
        </div>
    </div>

@prepend('linksPie')
    <script>
        var map;
        var marker;

        console.log("{{ $lat.'--'.$lon }}")

        var lat={{ $lat }};
        var lng={{ $lon }};
        
        function initMap() {
            var myLatLng = {
                lat: lat,
                lng: lng
            }
            map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                zoom: 10,
                mapTypeId: 'hybrid'
            });
            var marker = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                draggable: true,
                position: myLatLng,
                title: "Ubicación",
            });
           if(lat){
            marker.setMap(map);
           }
            
           

        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0Ko6qUa0EFuDWr77BpNJOdxD-QLstjBk&callback=initMap">
    </script>

    <style type="text/css">
        #map {
            height: 650px;
            width: auto;
        }
    </style>
@endprepend

@endsection
