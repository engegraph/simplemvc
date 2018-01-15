/** Definindo altura altomática para textareas **/
autosize($('textarea.autosize'));

/**
 * Datetime  Picker, janela de seleção de datas
 * @link https://eonasdan.github.io/bootstrap-datetimepicker/
 */
$('.datetime').datetimepicker({
    locale: 'pt-br',
    format: 'DD/MM/YYYY'
});

/**
 * Mostra o load na página
 * @type {{show: wSGILoad.show, hide: wSGILoad.hide}}
 */
var wSGILoad = {
    show : function(){
        $('body').addClass('wSGI-load');
    },
    hide : function(){
        $('body').removeClass('wSGI-load');
    }
};

$('div.btn-actions').find('button[name=_save]').on('click', function (e) {
    wSGILoad.show();
});

/**
 * Função para requisição ajax
 * @param method
 * @param options
 */
function request(method, options){
    if(typeof method !== 'string')
        throw('method deve ser uma string');

    if(typeof options !== 'object')
        throw('options deve ser um objeto');

    options.type = 'POST';
    options.dataType = 'JSON';
    options.cache = options.hasOwnProperty('cache') ? options.cache : false;
    options.contentType = options.hasOwnProperty('contentType') ? options.contentType : 'application/x-www-form-urlencoded; charset=UTF-8';
    options.processData = options.hasOwnProperty('processData') ? options.processData : true;
    options.headers = {
        'X-wSGI-Request' : method
    };
    /*options.beforeSend = options.beforeSend || function () {

     };*/
    options.success = options.success || function (response) {
            if(response.redirect){
                window.location.replace(response.redirect);
            } else if(response.alert){
                message(response.alert);
            }
        };
    options.complete = function () {
        wSGILoad.hide();
    };
    options.error = options.error || function (jqXHR, textStatus, errorThrown) {
            message({type:'danger', content: jqXHR.responseText});
        };

    wSGILoad.show();
    $.ajax(options);
}