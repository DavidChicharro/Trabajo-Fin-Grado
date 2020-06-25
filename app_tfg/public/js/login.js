//Si los campos ya están rellenos se habilita el
// botón de inicio de sesión
$( document ).ready(function() {
    setInterval(
        function() { checkAllowLogin($(document).find('form')); },
        750
    );
});

function allowLogin(form){
    let allowLoginForm = false;
    let validEmail = false;
    let validPass = false;

    form.find('input').each(function () {
        let input = $(this);

        if (input.attr('type') === 'email') {
            let emailRegExp = new RegExp('^(([^<>()\\[\\]\\\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$');
            validEmail = emailRegExp.test(input.val());
        }
        if (input.attr('type') === 'password') {
            validPass = input.val().length >= 8;
        }

        if(validEmail && validPass)
            allowLoginForm = true;
    });
    return allowLoginForm;
}

//Habilita o deshabilita el botón de inicio de sesión
// según si se cumplen las restricciones
function checkAllowLogin(form) {
    if (allowLogin(form))
        $('input[type=submit]').prop("disabled",false);
    else
        $('input[type=submit]').prop("disabled",true);
}

//Cada vez que se presiona una tecla en el campo de la
// contraseña se comprueba si el formato de email es válido
// y si hay más de 8 caracteres en el campo de la contraseña
$('input[type=password]').keyup(function () {
    let formParent = $(this).closest("form");
    checkAllowLogin(formParent);
});

//Cada vez que se pierde el foco en el campo del email
// se comprueba si el formato de este es válido y si
// hay más de 8 caracteres en el campo de la contraseña
$('input[type=email]').blur(function () {
    let formParent = $(this).closest("form");
    checkAllowLogin(formParent);
});
