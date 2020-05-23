$(document).ready(function () {
    $('#radio-value').html( $('#radio_zona_int').val()+' m');
});

const mymap = L.map('mapid').setView([37.18,-3.6], 14);
const areaLayerGroup = L.layerGroup().addTo(mymap);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
    maxZoom: 22,
    id: 'mapbox/streets-v11',
    accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
}).addTo(mymap);

var searchControl = new L.esri.Controls.Geosearch().addTo(mymap);

var radius;
mymap.on('click', function (e) {
    let latLng = mymap.mouseEventToLatLng(e.originalEvent);
    areaLayerGroup.clearLayers();

    $('#lat_zona_int').val(
        parseFloat(latLng.lat).toFixed(4)
    );
    $('#long_zona_int').val(
        parseFloat(latLng.lng).toFixed(4)
    );
    let placeName = "";
    $.ajax({
        url: 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' +
            latLng.lat + '&lon=' + latLng.lng,
        success: function(data){
            placeName = ((typeof(data.address.locality)!== 'undefined')?data.address.locality +", ":"")+
                ((typeof(data.address.city_district)!== 'undefined')?data.address.city_district +", ":"")+
                ((typeof(data.address.village)!== 'undefined')?data.address.village:"")+
                ((typeof(data.address.town)!== 'undefined')?data.address.town:"")+
                ((typeof(data.address.city)!== 'undefined')?data.address.city:"")+
                ((typeof(data.address.county)!== 'undefined')?" ("+data.address.county +")":"");

            $('#nombre_lugar').val((placeName !== "")?placeName:data.address.country);
        },
        error : function() {
            placeName = latLng.lat + ', ' + latLng.lng;
            $('#nombre_lugar').val(placeName);
        }
    });

    L.marker(latLng).addTo(areaLayerGroup);
    radius = L.circle(latLng, parseInt($('#radio_zona_int').val()), {color: "red"}).addTo(areaLayerGroup);

    if($('#slider-radio').is(":hidden")){
        $('#slider-radio').removeAttr('hidden');
        $('input[type=submit]').removeAttr('disabled');
    }
});

// Actualizo el valor del radio al lado del slider y la amplitud en el mapa
$(document).on('input', '#radio_zona_int', function () {
    let newRadius = $(this).val();
    $('#radio-value').html( newRadius+' m');
    radius.setRadius(newRadius);
});
