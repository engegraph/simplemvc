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

/**
 * Configuração de boxes de mensagens
 * @param options
 */
function message (options) {

    var settings = {
        'type'    : options.type || 'warning',
        //'title' : options.title ? options.title : (options.type === 'danger' ? 'Algo errado não está certo' : 'wSGI Informação'),
        //'icon' : options.icon ? options.icon : (options.type),
        'content' : options.content || 'Problemas aconteceram. Mais detalhes não estão disponíveis.',
        'time' : options.time || null
    };

    if(!options.icon){
        if(settings.type==='danger')
            settings.icon = 'fa-close';
        else if(settings.type==='success')
            settings.icon = 'fa-check';
        else if(settings.type==='warning')
            settings.icon = 'fa-exclamation';
        else if(settings.type==='info')
            settings.icon = 'fa-info';
        else
            settings.icon = 'fa-bell';
    }
    else
        settings.icon = options.icon;

    if(!options.title){
        if(settings.type==='success')
            settings.title = 'Sucesso';
        else if(settings.type==='info')
            settings.title = 'wSGI Informa';
        else if(settings.type==='warning')
            settings.title = 'wSGI Alerta';
        else
            settings.title = 'Ops !';
    }
    else
        settings.title = options.title;

    settings.icon = 'fa '+settings.icon;

    /*if(options.time)
     settings.timeout = options.time;*/
    settings.timeout = '7000';

    if(settings.type==='danger'){
        settings.color = '#C26565';
    }else if(settings.type === 'warning'){
        settings.color = '#DFB56C';
    } else if(settings.type === 'info'){
        settings.color = '#9CB4C5';
    } else if(settings.type === 'success'){
        settings.color = '#8AC38B';
    }

    $.smallBox(settings);
}