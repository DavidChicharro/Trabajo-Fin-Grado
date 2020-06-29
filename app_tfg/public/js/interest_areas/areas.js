var favMarker = L.icon({
    iconUrl: window.location.origin+'/images/markers/marker-fav.png',
    iconAnchor: [15, 40],
});

// Devuelve las zonas de interés del usuario
function getInterestAreas() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/get_interest_areas',
        type: 'post',
        success: function (response) {
            if(!response.empty) {
                let result = JSON.parse(response.interestAreas);
                $.each(result, function (index, value) {
                    let marker = L.marker([value.latitud_zona_interes, value.longitud_zona_interes],
                        {icon: favMarker})
                        .bindPopup('<b>' + value.nombre_zona_interes + '</b><br>'
                            + 'Radio: ' + value.radio_zona_interes + ' m');
                    let radius = L.circle([value.latitud_zona_interes, value.longitud_zona_interes],
                        value.radio_zona_interes, {color: "red"});
                    intAreasLayerGroup.addLayer(L.layerGroup([marker, radius]));
                });

                let bounds = JSON.parse(response.bounds);
                mymap.fitBounds([
                    [bounds.south, bounds.west],
                    [bounds.north, bounds.east]
                ], {maxZoom: 15});

                fillOptions(response.interestAreas);
            }else{
                location.reload();
            }
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
}

const mymap = L.map('mapid');
mymap.on('load', function () {
    getInterestAreas();
});
mymap.setView([37.18,-3.6], 14);
const intAreasLayerGroup = L.layerGroup().addTo(mymap);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
    maxZoom: 22,
    id: 'mapbox/streets-v11',
    accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
}).addTo(mymap);


function fillOptions(zonas){
    let output = "";
    JSON.parse(zonas).forEach(function (e) {
        let idOpt = "";
        let valOpt = "";
        $.each(e, function (key, value) {
            if(key==='id')
                idOpt = value;
            else if(key==='nombre_zona_interes')
                valOpt = value;
        });
        output+='<option value="'+idOpt+'">'+valOpt+'</option>';
    });
    $('#zonas_interes').html(output);
    $('#zonas_interes').selectpicker('refresh');
}

// Devuelve el valor del elemento seleccionado en el menú desplegable
$(function () {
    $("#zonas_interes").on("changed.bs.select", function(e, clickedIndex, newValue, oldValue) {
        // console.log(this.value, clickedIndex, newValue, oldValue);
        let bin = $('[id^=remove-interest-area]');
        bin.removeClass('d-none');
        bin.attr('id', 'remove-interest-area-'+this.value);
    });
});

// Elimina la zona de interés al clicar en la papelera
$('[id^=remove-interest-area]').click(function () {
    if (window.confirm("¿Quieres eliminar esta zona de interés?")) {
        let idIntArea = $(this).attr('id').split('-')[3];
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/remove_interest_area',
            data: {
                'idIntArea': idIntArea
            },
            type: 'post',
            success: function (response) {
                if (response === "success") {
                    getInterestAreas();
                    intAreasLayerGroup.clearLayers();
                    let bin = $('[id^=remove-interest-area]');
                    bin.addClass('d-none');
                    bin.attr('id', 'remove-interest-area');
                }
            },
            statusCode: {
                404: function () {
                    alert('web not found');
                }
            },
        });
    }
});
