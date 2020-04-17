var validName = false;
var validSurnames = false;
var validDNI = false;
var validDate = false;
var validTlf = false;

function calcDniLetter(number){
    const ctrlCharArr = "TRWAGMYFPDXBNJZSQVHLCKET";
    let position = number%23;
    return ctrlCharArr.charAt(position);
}

function allowRegisterStp2(input) {
    switch (input.attr('name')) {
        case 'nombre':
            validName = (input.val().length>1 && input.val().length<255);
            break;
        case 'apellidos':
            validSurnames = (input.val().length>1 && input.val().length<255);
            break;
        case 'dni':
            let dni = input.val();
            let dniCtrl = calcDniLetter(dni.substr(0,8));
            let dniRegExp = new RegExp('^[0-9]{8}(-)?['+dniCtrl+','+dniCtrl.toLowerCase()+']$');
            validDNI = dniRegExp.test(dni);
            if(!validDNI)
                $('#invalid-dni').show();
            else
                $('#invalid-dni').hide();
            break;
        case 'fecha_nacimiento':
            let minDate = new Date(new Date().setFullYear(new Date().getFullYear()-12));
            let maxDate = new Date('1900');
            let birthDate = new Date(input.val());
            validDate = (birthDate<=minDate && birthDate>maxDate);
            break;
        case 'telefono':
            let telRegExp = new RegExp('^[6,7][0-9]{8}$');
            validTlf = telRegExp.test(input.val());
            break;
    }

    return (validName && validSurnames && validDNI && validDate && validTlf);
}

function checkAllowRegisterStp2(input){
    if(allowRegisterStp2(input)){
        $('input[type=submit]').prop("disabled",false);
    }else
        $('input[type=submit]').prop("disabled",true);
}


$('input').change(function () {
    checkAllowRegisterStp2($(this));
});

$('input[name=telefono]').keyup(function () {
    checkAllowRegisterStp2($(this));
});

function capitalizeFirst(text){
    return text.charAt(0).toUpperCase() + text.substring(1);
}

$('form').submit(function () {
    $(this).find('input').each(function () {
        let input = $(this);

        switch (input.attr('name')) {
            case 'nombre':
                input.val(capitalizeFirst(input.val()));
                break;
            case 'apellidos':
                input.val(capitalizeFirst(input.val()));
                break;
            case 'dni':
                let dniNum = input.val().substr(0,8);
                let dniCtrlChar = input.val().substr(-1).toUpperCase();
                input.val(dniNum+'-'+dniCtrlChar);
        }
    });
});