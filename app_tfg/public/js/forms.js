// $('.email-input').blur(function () {
//     alert("hander blur called");
// });

// $('input[name=email]').blur(function () {
//     alert("hander blur called");
// });
function validateEmail(email) {
    console.log(email);
}

function validateForm(form){
    //para cada elemento del formulario...
    form.find('input').each(function () {
        let input = $(this);
        //alert('Soy '+input.val());
        // console.log(input);
        switch (input.attr('type')) {
            case 'email':
                //validate email
                validateEmail($(this));
                break;
            case 'password':
                //validate pass
                break;
            default:
                //
                break;
        }
    });
}

//Cuando se abandona cualquier campo
// $('input').blur(function () {
//     let formParent = $(this).closest("form");
//     validateForm(formParent);
// });


// function allowSubmit(form){
//     let allowSubmitForm = false;
//     let validEmail = false;
//     let validPass = false;
//
//     form.find('input').each(function () {
//         let input = $(this);
//
//         if (input.attr('type') === 'email') {
//             let emailRegExp = new RegExp('^(([^<>()\\[\\]\\\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$');
//             if( emailRegExp.test(input.val()) ){
//                 validEmail = true;
//             }
//         }
//         if (input.attr('type') === 'password') {
//             // let passRegExp = new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[\\W|_])[A-Za-z\\d\\W|_]{8,}$');
//             let passRegExp = new RegExp('^[A-Za-z\\d\\W|_]{8,}$');
//             if( passRegExp.test(input.val()) ){
//                 validPass = true;
//             }
//         }
//
//         if(validEmail && validPass)
//             allowSubmitForm = true;
//     });
//     return allowSubmitForm;
// }

//Cuando se abandona cualquier campo
// $('input').blur(function () {
//     let formParent = $(this).closest("form");
//     if(allowSubmit(formParent)){
//         console.log(true);
//         $('input[type=submit]').prop("disabled",false);
//     }
// });
//
// $('input[type=password]').keyup(function () {
//     let formParent = $(this).closest("form");
//     if(allowSubmit(formParent)){
//         console.log(true);
//         $('input[type=submit]').prop("disabled",false);
//     }
// });