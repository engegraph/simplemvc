$(document).ready(function () {

    /**
     * Recuperando formulários de interação
     * @type {*|jQuery|HTMLElement}
     */
    var formLogin = $('form#wsgi-form-login'), formRegister = $('form#wsgi-form-register'), formRecover = $('form#wsgi-form-recover');

    /**
     * envia dados do usuário para atutenticar
     */
    $(formLogin).on('submit', function (e) {
        e.preventDefault();
        var data = new FormData(this);
        request('onAuth',{
            data: data,
            contentType: false,
            processData: false
        });
    });


    /**
     * Envia dados do usuário para registro
     */
    $(formRegister).on('submit', function (e) {
        e.preventDefault();
        var data = new FormData(this);
        request('onRegister',{
            data : data,
            contentType: false,
            processData: false
        });
    });


    /**
     * Envia dados do usuário para recuperação
     */
    $(formRecover).on('submit', function (e) {
        e.preventDefault();
        var data = new FormData(this);
        request('onRecover',{
            data : data,
            contentType: false,
            processData: false
        });
    })
});