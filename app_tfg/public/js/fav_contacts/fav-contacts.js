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
    if ($('#contacts').hasClass('d-none'))
        $('#contacts').removeClass('d-none');

    let content = "";
    if (response !== "") {
        if (response.is_fav === false) {
            content = '<h4 class="float-left p-1">' + response.name + '</h4>' +
                '<img src="' + addContactIcon + '" id="add-fav-contact-' + response.id +
                '" class="float-left ml-3 icon-pointer" width="35px">';

        } else {
            let postContent = "";
            if(response.is_fav === 0)
                postContent = '<span class="h6 float-left text-muted ml-3 mt-1 border rounded p-1">Solicitud enviada</span>';
            else if(response.is_fav === 1)
                postContent = '<span class="h2 float-left my-auto" data-toggle="tooltip" data-placement="right" '+
                    'title="Ya es tu contacto favorito">  &#10003;</span>';
            else
                postContent = '<img src="' + addContactIcon + '" id="readd-fav-contact-' + response.id +
                    '" class="ml-3 float-left icon-pointer" width="35px">';

            content = '<span class="h4 float-left p-1">' + response.name + '</span>' + postContent;
        }
    } else {
        content = '<p class="h6 p-2">No se han encontrado usuarios</p>';
    }

    $('#contacts').html(content);
}

// Añadir un nuevo contacto
$(document).on("click","[id*=add-fav-contact-]", function () {
    let userId = $(this).attr('id').split('-')[3];
    let notFirstPetition = ($(this).attr('id').split('-')[0] === 'readd');
    let elem = $(this);

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/add_fav_contact',
        data: {
            'userId': userId,
            'petition': notFirstPetition
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

// Eliminar un contacto favorito
$(document).on("click","[id*=delete-fav-contact-]", function () {
    let swapContent = false;
    let splittedId = $(this).attr('id').split('-');
    let userId = "";

    // Si un usuario se elimina como contacto favorito
    if(splittedId[0] === 'i') {
        userId = splittedId[4];
        swapContent = true;
    }else
        userId = splittedId[3];

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/delete_reject_fav_contact',
        data: {
            'userId': userId,
            'swap': swapContent
        },
        type: 'post',
        success: function (response) {
            if(response==="success")
                location.reload();
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
});

$("#sortable").sortable({
    stop: function () {
        let cont = 1;
        $('.order-contact').each(function(){
            $(this).find('.list-order').html(cont);
            cont++;
        });
    }
});

$('#btn-sort-contacts').click(function () {
    let order = $('#sortable').sortable('toArray');
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/ordenar-contactos-favoritos',
        data: {
            'order': order
        },
        type: 'post',
        success: function (response) {
            if(response==="success"){
                $('#alert-success').removeClass('d-none');
            }else{
                $('#alert-error').removeClass('d-none');
            }
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
});
