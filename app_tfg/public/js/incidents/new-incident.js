const mymap = L.map('mapid').setView([37.18,-3.6], 14);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
        '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
        'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
    maxZoom: 22,
    id: 'mapbox/streets-v11',
    accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
}).addTo(mymap);

var searchControl = new L.esri.Controls.Geosearch().addTo(mymap);
var incidentsLayerGroup = new L.LayerGroup().addTo(mymap);

mymap.on('click', function (e) {
    let latLng = mymap.mouseEventToLatLng(e.originalEvent);
    incidentsLayerGroup.clearLayers();

    $('#lugar').val(
        parseFloat(latLng.lat).toFixed(4) +','+
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

    L.marker(latLng).addTo(incidentsLayerGroup);
});

// Convierte a mayúscula la primera letra de la cadena de caracteres
const capitalize = (s) => {
    if (typeof s !== 'string') return '';
    return s.charAt(0).toUpperCase() + s.slice(1);
};

// Rellena el desplegable con las opciones de categorías de delitos
function fillOptions(delitos){
    let output = "";
    delitos.forEach(function (e) {
        let idOpt = "";
        let valOpt = "";
        $.each(e, function (key, value) {
            if(key==='id')
                idOpt = value;
            else if(key==='nombre_delito')
                valOpt = capitalize(value);
        });
        output+='<option value="'+idOpt+'">'+valOpt+'</option>';
    });
    $('#delito').html(output);
    $('#delito').selectpicker('refresh');
}

// Obtiene los grupos de delitos existentes
$('#search-delitos').click(function(){
    let categDelitos = $('#categ-delito').val();

    if(categDelitos.length > 0){
        $.ajax({
            url: '/get_delitos',
            data: {
                'delitos': categDelitos
            },
            type: 'get',
            success: function (response) {
                $('#delito').selectpicker('deselectAll');
                fillOptions(response);
            },
            statusCode: {
                404: function () {
                    alert('web not found');
                }
            },
        });
    }else{
        $('#delito').html("");
        $('#delito').selectpicker('refresh');
    }
});