function expandIncident(tabla, incidente){
    let descRow = '<tr class="expanded"><td colspan="2" class="pt-3">'+incidente.descripcion+'</td></tr>';
    let shareLessRow = '<tr class="expanded"><td class="pt-4"><div class="share-incident sp-as-lk px-3"><img class="icon-img" src="'+shareUrl+'"><span>Compartir incidente</span></div></td><td class="text-right pt-4"><span id="vl" class="view-less text-right sp-as-lk">Ver menos</span></td></tr>';
    tabla.after(shareLessRow).delay(500);
    tabla.after(descRow).delay(500);
}

function contractIncident(){
    $(".expanded").hide(300, "linear"); //Oculta todas las rows
    $(".view-more-loaded").show(300);   //Muestro botón "ver más" del resto
    $(".expanded").closest("article").css("background-color", "white");
}

$(document).on("click",".view-less", function () {
    contractIncident();
});

$(".view-more").click(function() {
    contractIncident();
    $(this).hide(); //escondo boton "Ver más"
    $(this).closest("article").css("background-color", "var(--k-blue-op10)");

    if(!$(this).hasClass("view-more-loaded")) {
        $(this).addClass("view-more-loaded");
        let buttonId = $(this).attr('id');
        let incidentId = buttonId.replace('vm', '');
        let tabla = $(this).closest("tbody");

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/get_incident_details',
            data: {
                // '_token': "{{csrf_token()}}",
                'incidentId': incidentId
            },
            type: 'post',
            success: function (response) {
                expandIncident(tabla, response.incidente);
            },
            statusCode: {
                404: function () {
                    alert('web not found');
                }
            },
        });
    }
    let antecessor = $(this).closest("tbody");console.log(antecessor);
    $(antecessor).siblings(".expanded").show(300);
});