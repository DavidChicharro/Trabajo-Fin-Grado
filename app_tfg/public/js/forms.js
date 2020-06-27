function validateEmail(email) {
    console.log(email);
}

function validateForm(form){
    //para cada elemento del formulario...
    form.find('input').each(function () {
        let input = $(this);
        switch (input.attr('type')) {
            case 'email':
                validateEmail($(this));
                break;
            case 'password':
                break;
            default:
                break;
        }
    });
}
