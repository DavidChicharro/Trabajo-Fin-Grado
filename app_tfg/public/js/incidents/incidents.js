function expandIncident(tabla, incidente, incId, offId) {
    let descRow = '<tr class="expanded"><td colspan="2" class="pt-3">'+incidente.descripcion+'</td></tr>';

    let adminHideRemove = admin ? '<div class="hide-remove float-right">' +
        '<span class="hide-inc sp-as-lk text-warning ml-5" id="hideinc-'+incId+'-'+offId+'">Ocultar</span>' +
        '<span class="rem-inc sp-as-lk text-k-red ml-4" id="reminc-'+incId+'-'+offId+'">Eliminar</span></div>' : '';

    let shareLessRow = '<tr class="expanded"><td class="pt-4">' +
        '<div class="share-incident sp-as-lk px-3 float-left" id="shinc-'+incId+'-'+offId+'">' +
        '<img class="icon-img" src="'+shareUrl+'"><span>Compartir incidente</span></div>' +
        adminHideRemove + '</td>' +
        '<td class="text-right pt-4"><span id="vl" class="view-less text-right sp-as-lk">Ver menos</span>' +
        '</td></tr>';

    tabla.after(shareLessRow).delay(500);
    tabla.after(descRow).delay(500);
}

function contractIncident() {
    $(".expanded").hide(300, "linear"); //Oculta todas las rows
    $(".view-more-loaded").show(300);   //Muestro botón "ver más" del resto
    $(".expanded").closest("article").css("background-color", "white");
}

$(document).on("click", ".view-less", function () {
    contractIncident();
});

$(".view-more").click(function() {
    contractIncident();
    $(this).hide(); //escondo boton "Ver más"
    $(this).closest("article").css("background-color", "var(--k-blue-op10)");

    if(!$(this).hasClass("view-more-loaded")) {
        $(this).addClass("view-more-loaded");
        let buttonId = $(this).attr('id');
        let spltId = buttonId.split('-');
        let incidentId = spltId[1];
        let offenceId = spltId[2];
        let tabla = $(this).closest("tbody");

        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/get_incident_details',
            data: {
                'incidentId': incidentId,
                'offenceId': offenceId,
            },
            type: 'get',
            success: function (response) {
                expandIncident(tabla, response.incidente, incidentId, offenceId);
            },
            statusCode: {
                404: function () {
                    alert('web not found');
                }
            },
        });
    }
    let ancestor = $(this).closest("tbody");
    $(ancestor).siblings(".expanded").show(300);
});

$(document).on("click", ".share-incident", function () {
    let shId = $(this).attr('id');
    let spltShInc = shId.split('-');

    let url = 'https://www.kifungo.live/incidente?inc='+spltShInc[1]+'%26del='+spltShInc[2];
    let twUrl = "https://twitter.com/intent/tweet?url=" + url + "&text=" + 'Nuevo incidente: ';
    let fbUrl = "https://www.facebook.com/sharer.php?u=" + url;

    $('.twitter-link').attr('href', twUrl);
    $('.facebook-link').attr('href', fbUrl);

    $('#shareIncident').modal();
});

$(document).on("click", ".hide-inc", function () {
    let hideBtn = $(this);
    let hideId = $(this).attr('id');
    let spltHideInc = hideId.split('-');

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/ajax_hideIncident',
        data: {
            'incidentId': spltHideInc[1],
            'offenceId': spltHideInc[2],
        },
        type: 'post',
        success: function (response) {
            if (response.status === 'success') {
                let shareInc = hideBtn.parent().prev();

                // Desabilita la opción de compartir el incidente
                shareInc.addClass('disabledButton');
                // Muestra al lado del título información para indicar que está oculto
                $('#title-inc-' + spltHideInc[1]+ '-' + spltHideInc[2]).append('<small>(oculto)</small>');
                // Cambia el botón de "Ocultar" por uno de "Mostrar"
                hideBtn.replaceWith('<span class="show-inc sp-as-lk text-success ml-5" id="showinc-' + spltHideInc[1] + '-' + spltHideInc[2] + '">Mostrar</span>');
            }
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
});

$(document).on("click", ".rem-inc", function () {
    let remId = $(this).attr('id');
    let spltRemInc = remId.split('-');
    // console.log(spltRemInc[1], spltRemInc[2]);
    // console.log(admin);
});
