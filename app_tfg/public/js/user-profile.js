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
                console.log('type in new_password');
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
    console.log('typing in pass field');
    // let formParent = $(this).closest("form");
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