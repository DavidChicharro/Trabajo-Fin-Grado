$(function(){
    // Enables popover
    $("[data-toggle=popover]").popover({
        html: true,
        placement: 'bottom',
        content: function () {
            return $('#popover-content').html();
        },
        title: function () {
            return '<h5 class="text-center">Notificaciones</h5>';
        }
    });
    // .on('show.bs.popover', function () {
    //
    // });
});

function markAsRead(parentId) {
    let notificationId = parentId.split('_')[1];

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/mark_notification_as_read',
        data: {
            'notificationId': notificationId
        },
        type: 'post',
        success: function (response) {
            if(response>=0) {
                let popover = $("[data-toggle=popover]").data('bs.popover');

                if(response==0) {
                    popover.config.content = "No tienes ningunta notificación pendiente";
                    $('#notif-badge').remove();
                }else{
                    $('#notif-badge').text(response);
                }

                popover.hide();
            }
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
}

// Clico en marcar notificación como leída (notificación de incidente en zona de interés)
$(document).on("click","[id*=iai-]", function () {
    let divParentId = $(this).closest('[class*=notification_]').attr('id');
    markAsRead(divParentId);
});

// Clico en aceptar ser contacto favorito
$(document).on("click","[id*=bfc-]", function () {
    let splitId = $(this).attr('id').split('-');
    let divParentId = $(this).closest('[class*=notification_]').attr('id');

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/accept_favourite_contact',
        data: {
            'userId': splitId[1],
            'favContactId': splitId[2]
        },
        type: 'post',
        success: function (response) {
            console.log(response);
            if(response==="success") {
                markAsRead(divParentId);
                $('[class*='+divParentId+']').remove();
            }
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
});

// Clico en rechazar ser contacto favorito
$(document).on("click","[id*=notfc-]", function () {
    let splitId = $(this).attr('id').split('-');
    let divParentId = $(this).closest('[class*=notification_]').attr('id');

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/delete_reject_fav_contact',
        data: {
            'contactId': splitId[1],
            'senderUserId': splitId[2]
        },
        type: 'post',
        success: function (response) {
            console.log(response);
            if(response==="success") {
                markAsRead(divParentId);
                $('[class*='+divParentId+']').remove();
            }
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
});
