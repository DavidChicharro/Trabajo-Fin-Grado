function getIncidents(bounds, delitTypes=[], dateFrom="", dateTo="") {
    let mapLimits = bounds.split(',');
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/get_map_incidents',
        data: {
            'mapLimits': mapLimits,
            'delitTypes': delitTypes,
            'dateFrom': dateFrom,
            'dateTo': dateTo
        },
        type: 'post',
        success: function (response) {
            // console.log(response);
            let result = JSON.parse(response);
            $.each(result, function(index, value){
                L.marker([value.latitud, value.longitud])
                    .bindPopup('<b>'+value.incidente+'</b>  -  '+
                        value.fecha_hora+'.<br>'+value.nombre_lugar
                        +'<br>'+value.descripcion)
                    .addTo(incidentsLayerGroup);
            });
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
    getIncidents(mymap.getBounds().toBBoxString());
});
mymap.setView([37.18,-3.6], 14);
const incidentsLayerGroup = L.layerGroup().addTo(mymap);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
    maxZoom: 22,
    id: 'mapbox/streets-v11',
    accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
}).addTo(mymap);

// Tanto en cambio de zoom como al filtrar, se tienen en cuanta los filtros
function callGetIncidents() {
    getIncidents(mymap.getBounds().toBBoxString(),
        $('#tipos-incid').val(),
        $('#date-from').val(),
        $('#date-to').val()
    );
    incidentsLayerGroup.clearLayers();
}
// Cada cambio de zoom limpia los marcadores y recarga los nuevos
mymap.on('zoomend', function () {
    callGetIncidents();
});

$('#btn-filter-incident').click(function () {
    callGetIncidents();
});