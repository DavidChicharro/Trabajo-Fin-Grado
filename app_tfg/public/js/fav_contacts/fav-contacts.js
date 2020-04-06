function contractIncident(){
    $(".expanded").hide(300, "linear"); //Oculta todas las rows
    $(".view-less").hide(300);
    $(".view-more").show(300);   //Muestro botón "ver más" del resto
    $(".expanded").closest("article").css("background-color", "white");
}

$(document).on("click",".view-less", function () {
    contractIncident();
});

$(".view-more").click(function() {
    contractIncident();
    $(this).hide(); //escondo boton "Ver más"
    $(this).next().show(300);

    $(this).closest("article").css("background-color", "var(--k-blue-op10)");

    let antecessor = $(this).closest("tbody");
    $(antecessor).find(".expanded").show(300);
});

function showSearchContact(response) {
    console.log(response);
    if($('#contacts').hasClass('d-none'))
        $('#contacts').removeClass('d-none');

    let content = "";
    if(response !== "") {
        if (response.is_fav === false) {
            content = '<h4 class="float-left p-1">' + response.name + '</h4>' +
                '<img src="' + addContactIcon + '" id="add-fav-contact-' + response.id +
                '" class="float-left ml-3 icon-pointer" width="35px">';

        } else {
            let postContent = "";
            if(response.is_fav === 0)
                postContent = '<span class="h6 text-muted ml-3 mt-1 border rounded p-1">Solicitud enviada</span>';
            else
                postContent = '<span class="h2 my-auto" data-toggle="tooltip" data-placement="right" '+
                    'title="Ya es tu contacto favorito">  &#10003;</span>';

            content = '<span class="h4 p-1">' + response.name + '</span>' + postContent;
        }
    }else{
        content = '<p class="h6 p-2">No se han encontrado usuarios</p>';
    }

    $('#contacts').html(content);
}

$(document).on("click","[id*=add-fav-contact-]", function () {
    let userId = $(this).attr('id').split('-')[3];
    let elem = $(this);

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/add_fav_contact',
        data: {
            'userId': userId
        },
        type: 'post',
        success: function (response) {
            $(elem).replaceWith('<span class="h6 text-muted float-left ml-3 mt-1 border rounded p-1">Solicitud enviada</span>');
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
});

$("#btn-search-contact").click(function() {
    let contact = $('#contact-search').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/buscar_contacto',
        data: {
            'contact': contact
        },
        type: 'post',
        success: function (response) {
            showSearchContact(response);
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
});