

var widget = $('div.wsgi-datagrid'), datatable = $(widget).find('table.data-table'), btnRemove = $(widget).find('button#remove-checked');

$(datatable).on('click','.cell-check input[type=checkbox]', function (e) {
    var status = this.checked, isAll = $(this).prop('id') === 'toggle-all';
    if(isAll){
        $(datatable).find('td.cell-check input[type=checkbox]').each(function(index, element){
            if(element.checked !== status){
                $(element).trigger('click');
            }
        });
    }
    checkStatus();
});

function checkStatus() {
    var items = $(datatable).find('td.cell-check input[type=checkbox]:checked');
    if(items.length){
        $(btnRemove).removeClass('disabled').prop('disabled',false);
    } else {
        $(btnRemove).addClass('disabled').prop('disabled', true);
    }
}


/**
 * Captura o click na grid e abre o registro para edição
 */
$(datatable).on('click','td.cell-link', function (e) {
    var item = $(this).siblings('.cell-check');
    var link = $(item).find('a').prop('href');
    window.location.replace(link);
});


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
            settings.title = 'Algo errado não está certo';
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

/**
 * Mensagens de alertas das requisições normais
 * @param jsonString
 */
function showAlerts(jsonString) {
    if(jsonString){
        var objects = typeof jsonString === 'object'  ? jsonString : JSON.parse(jsonString);
        if(objects.length){
            var alerts = [];
            for(var i=0; i < objects.length; i++){
                var obj = objects[i], type = Object.keys(obj)[0];
                console.log(obj, type);
                alerts[type] = obj[type];
                //var alert = {type: type, content: obj[type]};
            }
            for(type in alerts){
                message({type: type, content: alerts[type]});
            }

        }
    }
}

/**
 * Janele de confirmação
 * @returns {number}
 */
function boxConfirm(length) {
    var id = Math.floor((Math.random() * 10) + 1 + length);
    var msg = 'Deseja realmente prosseguir com a eliminação d'+(length > 1 ? 'os registros selecionados' : 'o registro selecionado')+' ?';
    var sim = '<a href="javascript:void(0);" class="btn btn-primary btn-sm" id="rm_ok">Sim</a>';
    var nao = '<a href="javascript:void(0);" class="btn btn-danger btn-sm" id="rm_no">Não</a>';
    $.smallBox({
        title : "Espere !",
        content : msg+"<p id='"+id+"' class='text-align-right'>"+sim+'&nbsp;'+nao+"</p>",
        color : "#000",
        timeout: 10000,
        icon : "fa fa-hand-stop-o swing animated"
    });

    return id;
}
