$(function () {

    /**
     * Fixando tamanho da telas
     * @type {{show: show, hide: hide}}
     */
    var wsgiModal = {
        show: function () {
            $('body').css('overflow','hidden');
        },
        hide: function () {
            $('body').css('overflow','auto');
        }
    };

    /**
     * Definindo variáveis da aplicação
     * @type {string}
     */
    var targetList = 'data-controle', targetForm='data-modal-form', checkUuids = [], isSend = false,
        controleModal = $('#controleModal'),
        modalList = $(controleModal).find('#modalList'),
        modalForm = $(controleModal).find('#modalForm');

    /*
     * CONVERT DIALOG TITLE TO HTML
     * REF: http://stackoverflow.com/questions/14488774/using-html-in-a-dialogs-title-in-jquery-ui-1-10
     */
    $.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
        _title : function(title) {
            if (!this.options.title) {
                title.html("&#160;");
            } else {
                title.html(this.options.title);
            }
        }
    }));

    /**
     * Ouvindo clicks em links com o atributo data-controle
     */
    $('a['+targetList+']').on('click', function (e) {
        var modalTitle = $(this).attr('title');
        var modalName  = $(this).attr(targetList);
        var li = $(this).parent('li');
        $('a['+targetList+']').parent('li').removeClass('active');
        $(this).parent('li').addClass('active');
        configModalList(modalName, modalTitle);
    });

    /**
     * Ouvindo click em botões dos modals para abrir formulário
     */
    $('a['+targetForm+'], button['+targetForm+']').on('click', function (e) {
        var modalTitle = $(this).attr('title');
        var modelName = $(this).attr(targetForm);
        configModalForm('open', modelName, modalTitle);
    });


    function configModalList(modelName, modalTitle) {

        $(modalList).html('');

        var listDialog = $(modalList).dialog({
            autoOpen : false,
            width : 800,
            resizable : false,
            modal : true,
            title: $('<div>',{
                class:'widget-header',
                append: $('<h4>',{html: '<i class="fa fa-list"></i> Listando <strong>'+modalTitle+'</strong>'})
            }),
            show: {
                effect: "puff",
                duration: 200
            },
            hide: {
                effect: "puff",
                duration: 200
            },
            open: function () {
                wsgiModal.show();
            },
            close : function (event, ui) {
                $(modalList).html('');
                modelName = null;
                configModalForm('close');
                setTimeout(function(){
                    wsgiModal.hide();
                },500);
            },
            buttons : [{
                html : "<i class='fa fa-plus-square'></i>&nbsp; Novo",
                class : "btn btn-success",
                click : function () {
                    configModalForm('open', modelName, modalTitle);
                }
            }, {

                html : "<i class='fa fa-trash'></i>&nbsp; Remover",
                "class" : "btn btn-danger disabled",
                id: 'btn-remove-checked',
                disabled: 'disabled',
                click : function() {
                    onDeleteUuids();
                }
            }]
            //draggable: false
        });


        request('onGetModalModel',{
            data : {modelName: modelName},
            success : function(response){
                $(modalList).html(response.list);
                listDialog.dialog('open');
            }
        });


        /**
         * Capturando clicks nas colunas da listagem
         */
        $(document).on('click', 'table#modal-data-grid tr.row-data td.cell-link', function (e) {
            var row   = $(this).parent('tr');
            var check = $(row).find('input[type=checkbox]');
            $('table#modal-data-grid tr.row-data').removeClass('highlight');
            $(row).addClass('highlight');
            configModalForm('open', modelName, modalTitle, $(check).val());
        });


        /**
         * Trabalhando com os inputs checkbox na grid
         * Marcando e desmarcando registros
         */
        $(document).on('click', 'input#uuid-toggle', function () {
            var checked = this.checked;
            $(document).find('td.cell-uuid input').each(function (index, element) {
                if(checked !== $(element).prop('checked')){
                    $(element).trigger('click');
                }
            });
        });
        $(document).on('change', 'td.cell-uuid input', hasCheckedUuids);

    }
    
    
    function configModalForm(action, modelName, modalTitle, uuid) {

        var btnsForm = [
            {
                html : "<i class='fa fa-floppy-o'></i>&nbsp; <u>S</u>alvar",
                class : "btn btn-success",
                click : function() {
                    $('form.wSGI-form-modal').submit();
                }
            }
        ];

        if(uuid){
            btnsForm.unshift({
                html : "<i class='fa fa-trash-o'></i>&nbsp; <u>R</u>emover",
                class : "btn btn-link pull-left",
                click: function () {
                    onDeleteUuids([$('<input>',{type:'checkbox', name: modelName+'[Uuid][]', value: uuid})], true);
                }
            });
        }

        $(modalForm).html('');

        var formDialog = $(modalForm).dialog({
            autoOpen : false,
            width : 750,
            resizable : false,
            close: function () {
                $(modalForm).html('');
            },
            title: $('<div>',{
                class:'widget-header',
                append: $('<h4>',{html: '<i class="fa fa-'+(uuid ? 'edit' : 'plus')+'"></i> '+(uuid ? 'Alterar' : 'Criar ')+' registro'})
            }),
            show: {
                effect: "puff",
                duration: 200
            },
            hide: {
                effect: "puff",
                duration: 200
            },
            buttons : btnsForm
            //draggable: false
        });

        if(!modelName){
            formDialog.dialog('close');
            formDialog = null;
            return ;
        }


        /**
         * Capturando envio do formulário
         */
        $(document).on('submit', 'form.wSGI-form-modal', function (e) {

            e.preventDefault();
            var data = new FormData(this);
            var me = $(this);

            if(me.data('requestRunning'))
                return ;

            request('onSaveModel',{
                data: data,
                success: function (response) {
                    modalMessages(response.alerts);
                    if(response.success){
                        isSend++;
                        formDialog.dialog('close');
                        $(modalList).html(response.list);
                    }
                    me.data('requestRunning', false);
                },
                contentType: false,
                processData: false
            });

            me.data('requestRunning', true);

        });


        if(action==='open'){

            console.log(modelName);

            request('onGetModelForm',{
                data : {modelName: modelName, uuid: uuid},
                success: function (response) {
                    $(modalForm).html(response.form);
                    formDialog.dialog('open');
                }
            });

        } else {
            formDialog.dialog('close');
            formDialog = null;
        }
    }

    /**
     * Exibe Alertas
     * @param alerts
     */
    function modalMessages(alerts){
        if(alerts.length){
            for(var i in alerts){
                var alert = alerts[i];
                activeItemRequired(alert.fieldName);
                message(alert);
            }
        }
    }


    /**
     * Dar ênfase ao item requerido
     * @param name
     */
    function activeItemRequired(name) {
        var form = $('form.wSGI-form-modal'), element = $(form).find('\[name="'+name+'"\]');
        $(form).find('.form-group').removeClass('has-error');
        if(name){
            $(element).parent('.form-group').addClass('has-error');
            $(element).focus();
        }
    }

    /**
     * Recuperando checkbox selecionados
     */
    function hasCheckedUuids() {
        var btnRemove = $(document).find('button#btn-remove-checked');
        checkUuids = [];
        $(btnRemove).prop('disabled', true).addClass('disabled').focusout();
        $(document).find('td.cell-uuid input[type=checkbox]:checked').each(function (index, element) {
            checkUuids.push(element);
        });

        if(checkUuids.length){
            if($(btnRemove).prop('disabled') && btnRemove.hasClass('disabled')){
                $(btnRemove).prop('disabled', false).removeClass('disabled');
            }
        }
    }


    /**
     * Remove itemns
     * @param Uuids
     * @param closeDialog
     */
    function onDeleteUuids(Uuids, closeDialog) {
        if(Uuids && Uuids.length){
            checkUuids = Uuids;
        }
        if(checkUuids.length){

            var msg = 'Deseja realmente prosseguir com a eliminação d'+(checkUuids.length > 1 ? 'os registros selecionados' : 'o registro selecionado')+' ?';
            $(boxConfirm(msg)).on('click', function () {
                var data = new FormData, element = checkUuids.shift();
                var name = $(element).attr('name');
                data.append('modelName', name.substr(0, name.indexOf('[')));
                data.append(name, $(element).val());
                for(var i=0; i < checkUuids.length; i++){
                    element = checkUuids[i];
                    data.append(name, $(element).val());
                }
                request('onModelDelete', {
                    data : data,
                    success: function (response) {
                        if(response.success){
                            if(closeDialog){
                                configModalForm('close');
                            }
                            modalMessages(response.alerts);
                            $(modalList).html(response.list);
                            hasCheckedUuids();
                        }
                    },
                    contentType: false,
                    processData: false
                });
            });
        }
    }

});


