function allowSubmit(form){
    let allowSubmitForm = false;
    let validEmail = false;
    let validPass = false;

    form.find('input').each(function () {
        let input = $(this);

        if (input.attr('type') === 'email') {
            let emailRegExp = new RegExp('^(([^<>()\\[\\]\\\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$');
            if( emailRegExp.test(input.val()) ){
                validEmail = true;
            }
        }
        if (input.attr('type') === 'password') {
            // let passRegExp = new RegExp('^[A-Za-z\\d\\W|_]{8,}$');
            // if( passRegExp.test(input.val()) ){
            //     validPass = true;
            // }
            if(input.val().length >= 8)
                validPass = true;
        }

        if(validEmail && validPass)
            allowSubmitForm = true;
    });
    return allowSubmitForm;
}

// $('input').blur(function () {
//     let formParent = $(this).closest("form");
//     if(allowSubmit(formParent)){
//         $('input[type=submit]').prop("disabled",false);
//     }
// });

//Cada vez que se presiona una tecla en el formulario se comprueba
//si el formato de email se válido o si hay más de 8 caracteres
//en el campo de la contraseña
$('input[type=password]').keyup(function () {
    let formParent = $(this).closest("form");
    if(allowSubmit(formParent)){
        $('input[type=submit]').prop("disabled",false);
    }
});