function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: new google.maps.LatLng(48.8511618571692, 2.35382080078125),
    });

    var infoWindow = new google.maps.InfoWindow({map: map});

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Vous êtes ici !');
            map.setCenter(pos);
        }, function () {
            handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
        handleLocationError(false, infoWindow, map.getCenter());
    }


    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
    }

    var iconBase = 'https://maps.google.com/mapfiles/kml/shapes/';
    var icons = {
        parking: {
            name: 'Parking',
            icon: iconBase + 'parking_lot_maps.png'
        },
    };


    var features = [
        {
            position: new google.maps.LatLng(48.9566504, 2.475228300000026),
            type: 'parking'

        }, {
            position: new google.maps.LatLng(48.94870299999999, 2.526532999999972),
            type: 'parking'
        }, {
            position: new google.maps.LatLng(48.75662899999999, 2.371992999999975),
            type: 'parking'
        }, {
            position: new google.maps.LatLng(48.6287077, 2.427366900000038),
            type: 'parking'
        }, {
            position: new google.maps.LatLng(48.9358918898151, 2.3577475547790527),
            type: 'parking',
        }, {
            position: new google.maps.LatLng(48.7797306, 2.4574149000000034),
            type: 'parking',
        }, {
            position: new google.maps.LatLng(49.040924, 2.339091999999937),
            type: 'parking',
        }, {
            position: new google.maps.LatLng(48.9254954, 2.3287100999999666),
            type: 'parking',
        }, {
            position: new google.maps.LatLng(48.8819401, 2.4706525000000283),
            type: 'parking',
        }, {
            position: new google.maps.LatLng(48.83030290000001, 2.355218700000023),
            type: 'parking',
        }, {
            position: new google.maps.LatLng(48.75558229999999, 2.330474900000013),
            type: 'parking',
        }, {
            position: new google.maps.LatLng(48.8665239, 2.2832627999999886),
            type: 'parking',
        }, {
            position: new google.maps.LatLng(48.8951286, 2.251735999999937),
            type: 'parking',
        }, {
            position: new google.maps.LatLng(48.9274620030687, 2.4904632568359375),
            type: 'parking',
        }, {
            position: new google.maps.LatLng(48.919707506163014, 2.4514102935791016),
            type: 'parking',
        }
    ];



    for (var i = 0, feature; feature = features[i]; i++) {
        addMarker(feature);
    }

    function addMarker(feature) {
        var marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
            map: map
        });
        marker.addListener('click', function() {
            map.setZoom(18);
            map.setCenter(marker.getPosition());
        });
    }
}

function findAdress() {
    var geocoder = new google.maps.Geocoder();
    var adresse = document.getElementById('adresse').value;
    geocoder.geocode({'address': adresse}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
        } else {
            alert('Adresse introuvable: ' + status);
        }
    });
}
