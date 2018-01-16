$(document).ready(function () {

    var formLogin = $('#wsgi-form-login'), formRegister=$('#wsgi-form-register');

    $(formLogin).on('submit',function (e) {
        e.preventDefault();

        var data = new FormData(this);
        request('onAuth',{
            data: data,
            contentType: false,
            processData: false
        });
    })

});