var confPass = false;
var validPass = false;
var matchPass = false;

//Devuelve true si coinciden las contraseñas
function checkMatchPass(form) {
    let pass = form.find('input[name=new_password]');
    let passConf = form.find('input[name=conf_password]');

    return pass.val() === passConf.val();
}

function showHideAlertMsg(input, msg) {
    let id = 'id-'+input.attr('name');
    let form = input.closest('form');
    let errorElem = $(form).find('#'+id);

    if(msg === ""){
        if(errorElem.length !== 0)
            $(errorElem).remove();
    }else
        if(errorElem.length === 0){
            $(input).closest('.form-group')
                .after('<div id="'+id+'" class="form-group">' +
                    '<span class="text-danger">'+msg+'</span></div>');
        }
}

function checkChanges(input) {
    switch (input.attr('name')) {
        case 'nombre':
            if(input.val().length>1 && input.val().length<255){
                showHideAlertMsg(input,"");
            }
            else{
                let msg = "Formato de nombre inválido";
                showHideAlertMsg(input, msg);
            }
            break;
        case 'apellidos':
            if(input.val().length>1 && input.val().length<255){
                showHideAlertMsg(input,"");
            }
            else{
                let msg = "Formato de apellidos inválido";
                showHideAlertMsg(input, msg);
            }
            break;
        case 'email':
            let emailRegExp = new RegExp('^(([^<>()\\[\\]\\\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$');
            if(emailRegExp.test(input.val())){
                showHideAlertMsg(input,"");
            }
            else{
                let msg = "Formato de email inválido";
                showHideAlertMsg(input, msg);
            }
            break;
        case 'fecha_nacimiento':
            let minDate = new Date(new Date().setFullYear(new Date().getFullYear()-12));
            let maxDate = new Date('1900');
            let birthDate = new Date(input.val());
            if(birthDate<=minDate && birthDate>maxDate)
                showHideAlertMsg(input,"");
            else{
                let msg = "Fecha inválida";
                showHideAlertMsg(input, msg);
            }
            break;
        case 'telefono':
            let telRegExp = new RegExp('^[6,7][0-9]{8}$');
            if(telRegExp.test(input.val()))
                showHideAlertMsg(input,"");
            else{
                let msg = "Formato de teléfono móvil inválido";
                showHideAlertMsg(input, msg);
            }
            break;
        case 'telefono_fijo':
            let telFRegExp = new RegExp('^[8,9][0-9]{8}$');
            if(telFRegExp.test(input.val()))
                showHideAlertMsg(input,"");
            else{
                let msg = "Formato de teléfono fijo inválido";
                showHideAlertMsg(input, msg);
            }
            break;
        case 'new_password':
            if (input.attr('name') === 'new_password') {
                let passRegExp = new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[\\W|_])[A-Za-z\\d\\W|_]{8,}$');
                validPass = passRegExp.test(input.val());

                if (validPass)
                    showHideAlertMsg(input, "");
                else {
                    let msg = "La contraseña debe tener al menos una letra minúscula y mayúscula, un número y un caracter espacial";
                    showHideAlertMsg(input, msg);
                }
            }
    }
}

$('input').not('input[type=date]').keyup(function () {
    checkChanges($(this));
    $(this).focus();
});

$('input[type=date]').blur(function () {
    checkChanges($(this));
    $(this).focus();
});



function checkAllowChangePass(){
    if(validPass && matchPass){
        $('input[name=formPass]').prop("disabled",false);
    }else
        $('input[name=formPass]').prop("disabled",true);
}

//Cada vez que se presiona una tecla en el campo de la
// contraseña se comprueba si el formato contraseña es
// válido y si la nueva contraseña coincide con la confimación
$('input[type=password]').keyup(function () {
    checkChanges($(this));
    if(confPass){
        matchPass = checkMatchPass($(this).closest("form"));

        if(matchPass){
            showHideAlertMsg($(this),"");
        }else{
            let msg = "¡Las contraseñas no coinciden!";
            showHideAlertMsg($(this), msg);
        }
    }
    checkAllowChangePass();
});

$('input[name=conf_password]').focus(function () {
    confPass = true;
});


function printVarModal(modal, params){
    let title = "";
    switch (modal){
        case 'secretpin':
            title = 'Establecer PIN secreto';
            let pin = (params==null) ? "":params;
            $('#pin-number').val(pin);
            break;
        case 'panicact':
            title = 'Establecer acción de pánico';
            let action = (params==null) ? "":params;
            switch(action){
                case 'ubicacion':
                    $('#panicact-ubi').prop("checked",true);
                    break;
                case 'llamada':
                    $('#panicact-call').prop("checked",true);
                    break;
                default:
                    $('#panicact-not').prop("checked",true);
                    break;
            }
            break;
        default:
            alert('Error: no se ha podido recuperar el valor consultado.');
            break;
    }
    $('#config-title').html(title);
}

function getContentModal(modal){
    let content = "";
    switch (modal){
        case 'secretpin':
            content = '<span class="offset-2 col-2 text-left px-1 my-auto">PIN</span>\n' +
                '<input type="text" class="col-4 px-0" id="pin-number" maxlength="4" pattern="[0-9]{4}" title="4 números">';
            break;
        case 'panicact':
            content = '<div class="form-check offset-2 col-9 p-1">\n' +
                '    <input type="radio" id="panicact-not" name="panicact" value="NULL" class="form-check-input">\n' +
                '    <label for="not-act" class="form-check-label ml-2">Ninguna</label>\n' +
                '  </div>\n' +
                '  <div class="form-check offset-2 col-9 p-1">\n' +
                '    <input type="radio" id="panicact-ubi" name="panicact" value="ubicacion" class="form-check-input">\n' +
                '    <label for="not-act" class="form-check-label ml-2">Enviar ubicación</label>\n' +
                '  </div>\n' +
                '  <div class="form-check offset-2 col-9 p-1">\n' +
                '    <input type="radio" id="panicact-call" name="panicact" value="llamada" class="form-check-input">\n' +
                '    <label for="not-act" class="form-check-label ml-2">Llamar a contactos</label>\n' +
                '  </div>';
            break;
        default:
            alert('Error: no se ha podido establecer el modal.');
            break;
    }
    return content;
}

$(".open-modal").click(function() {
    let linkId = $(this).attr('id');
    let contentModal = getContentModal(linkId);
    let configContainer = $('.modal-body .row');
    configContainer.html(contentModal);
    configContainer.attr('id', linkId+"-container");

    $.ajax({
        url: '/user_config',
        data: {
            'params': linkId
        },
        type: 'get',
        success: function (response) {
            printVarModal(linkId,response);
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
});

function getInputValues(id){
    let inputValue = "";
    switch(id){
        case 'panicact':
            $('.modal-body .row :input[type=radio]').each(function(){
                if($(this).is(':checked'))
                    inputValue = $(this).val();
            });
            break;
        case 'secretpin':
            inputValue = $('.modal-body .row :input').val();
            break;
        default:
            break;
    }
    return inputValue;
}

$("#save-config").click(function () {
    let configId = $('.modal-body .row').attr('id');
    configId = configId.split("-container")[0];
    let inputValue = getInputValues(configId);
    if(configId === 'secretpin'){
        let pinRegExp = new RegExp('^[0-9]{4}$');
        let validPin = pinRegExp.test(inputValue);
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/user_config',
        data: {
            'configId': configId,
            'value': inputValue
        },
        type: 'post',
        success: function (response) {
            alert("Los parámetros se han actualizado correctamente");
        },
        statusCode: {
            404: function () {
                alert('web not found');
            }
        },
    });
});

$(document).on("keyup","#pin-number",function () {
    let pinRegExp = new RegExp('^[0-9]{4}$');
    let validPin = pinRegExp.test($(this).val());

    if(!validPin)
        $("#save-config").attr("disabled", true);
    else
        $("#save-config").attr("disabled", false);
});
