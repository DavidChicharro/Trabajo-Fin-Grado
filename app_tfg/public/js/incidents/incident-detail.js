$( document ).ready(function() {
    const mymap = L.map('mapid');

    mymap.setView([$('#lat').val(), $('#lng').val()], 15);
    const incidentsLayerGroup = L.layerGroup().addTo(mymap);
    L.marker([$('#lat').val(), $('#lng').val()])
        .addTo(incidentsLayerGroup);

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/" target="_blank">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/" target="_blank">Mapbox</a>',
        maxZoom: 22,
        id: 'mapbox/streets-v11',
        accessToken: 'pk.eyJ1IjoiZGF2aWRjaGljaGFycm8iLCJhIjoiY2s4dTRuenNqMDE5djNka2Q0amE3bHBnYyJ9.ebGkyWx_FQLj5oBW936UJg'
    }).addTo(mymap);
});
