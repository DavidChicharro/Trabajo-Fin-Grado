var confPass = false;

//Devuelve true si coinciden las contraseñas
function checkMatchPass(form) {
    let pass = form.find('input[name=password]');
    let passConf = form.find('input[name=conf_password]');

    return pass.val() === passConf.val();
}

function allowRegister(form){
    let allowRegisterForm = false;
    let validEmail = false;
    let validPass = false;
    let matchPass = false;

    form.find('input').each(function () {
        let input = $(this);

        if (input.attr('type') === 'email') {
            let emailRegExp = new RegExp('^(([^<>()\\[\\]\\\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$');
            validEmail = emailRegExp.test(input.val());
        }
        if (input.attr('type') === 'password') {
            let passRegExp = new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[\\W|_])[A-Za-z\\d\\W|_]{8,}$');
            validPass = passRegExp.test(input.val());

            if (input.attr('name') === 'password') {
                if (!validPass && $('#passFormat').hasClass('d-none')) {
                    $('#passFormat').removeClass('d-none').addClass('d-block');
                } else if (validPass && $('#passFormat').hasClass('d-block'))
                    $('#passFormat').removeClass('d-block').addClass('d-none');
            }

            // Si ya se ha puesto el foco alguna vez en confirmar
            // contraseña se puede comprobar si coinciden ambas
            if (confPass) {
                matchPass = checkMatchPass($(this).closest("form"));

                if (!matchPass) {
                    if($('#passMatch').hasClass('d-none'))
                        $('#passMatch').removeClass('d-none').addClass('d-block');
                } else {
                    if ($('#passMatch').hasClass('d-block'))
                        $('#passMatch').removeClass('d-block').addClass('d-none');
                }
            }
        }

        if(validEmail && validPass && matchPass)
            allowRegisterForm = true;
    });
    return allowRegisterForm;
}

//Habilita o deshabilita el botón de inicio de sesión
// según si se cumplen las restricciones
function checkAllowRegister(form){
    if(allowRegister(form)){
        $('input[type=submit]').prop("disabled",false);
    }else
        $('input[type=submit]').prop("disabled",true);
}

//Cada vez que se presiona una tecla en el campo de la
// contraseña se comprueba si el formato de email es válido
// y si hay más de 8 caracteres en el campo de la contraseña
$('input[type=password]').keyup(function () {
    let formParent = $(this).closest("form");
    checkAllowRegister(formParent);
});

//Cada vez que se pierde el foco en el campo del email
// se comprueba si el formato de este es válido y si
// hay más de 8 caracteres en el campo de la contraseña
$('input[type=email]').blur(function () {
    let formParent = $(this).closest("form");
    checkAllowRegister(formParent);
});

$('input[name=conf_password]').focus(function () {
    confPass = true;
});
