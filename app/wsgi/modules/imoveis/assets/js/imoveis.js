/**
 * Created by rodri on 22/12/2017.
 */

//Imovel - Adicionar pessoas no imovel
$("#EdNomePessoaAddImovel").autocomplete({
    source : function(res, response) {


        request('onCarregaPessoas',{
            data : {
                featureClass : "P",
                style : "full",
                maxRows : 12,
                name_startsWith : res.term
            },
            success : function(data) {


                if (data[0] == undefined)   {
                    data[0] = data;
                    //console.log(data[0]);
                } else {

                }


                response($.map(data, function(item) {
                    // console.log(typeof item);

                    if (typeof item === 'object')
                        var teste = {id: item.id, label : item.label,value : item.value}
                    //console.log(item);

                    return teste;
                }));

            }
        });

    },
    minLength : 2,
    select : function(event, ui) {
        //console.log(ui);
        $("#EdIdPessoaAddImovel").val(ui.item.id);
        $("#EdNomePessoaAddImovel").val(ui.item.label);
    }
});



//Imovel - Calculos valor aluguel
$("#EdPercentAdm").blur(function() {
    if (($('#EdValorAluguel').val() != '') && ($("#EdPercentAdm").val() != '')) {
        ValorAdmCalc = (parseFloat($("#EdPercentAdm").val()) / 100) * parseFloat($('#EdValorAluguel').val());
        ValorRepasseCalc = parseFloat($('#EdValorAluguel').val()) - ValorAdmCalc;

        $("#EdValAdm").val(ValorAdmCalc.toFixed(2).replace('.', ','))
        $("#EdValRepasse").val(ValorRepasseCalc.toFixed(2).replace('.', ','))
    }
});

$("#EdValorAluguel").on('blur',function(){
    if ($("#EdValorAluguel").val() != '') {
        ValorNum = parseFloat($("#EdValorAluguel").val());
        // console.log(ValorNum.toFixed(2).replace('.', ','));
        $("#EdValorAluguel").val(ValorNum.toFixed(2).replace('.', ','));
    }

    $("#EdPercentAdm").trigger("blur");
});
$("#EdValAdm").on('blur',function(){
    if ($("#EdValAdm").val() != '') {
        ValorNum = parseFloat($("#EdValAdm").val());
        // console.log(ValorNum.toFixed(2).replace('.', ','));
        $("#EdValAdm").val(ValorNum.toFixed(2).replace('.', ','));
    }

    $("#EdPercentAdm").trigger("blur");
});
$("#EdValRepasse").on('blur',function(){
    if ($("#EdValRepasse").val() != '') {
        ValorNum = parseFloat($("#EdValRepasse").val());
        // console.log(ValorNum.toFixed(2).replace('.', ','));
        $("#EdValRepasse").val(ValorNum.toFixed(2).replace('.', ','));
    }

    $("#EdPercentAdm").trigger("blur");
});

//Imovel - Calculos valor venda
$("#EdPercentComissao").blur(function() {
    if (($('#EdValorVenda').val() != '') && ($("#EdPercentComissao").val() != '')) {

        ValorComissaoCalc = (parseFloat($("#EdPercentComissao").val()) / 100) * parseFloat($('#EdValorVenda').val());
        ValorRepasseCalc  = parseFloat($('#EdValorVenda').val()) - ValorComissaoCalc;

        $("#EdValComissao").val(ValorComissaoCalc.toFixed(2).replace('.', ','))
        $("#EdValRepasseVenda").val(ValorRepasseCalc.toFixed(2).replace('.', ','))
    }

    if (($('#EdValorVenda').val() != '') && ($("#EdPercentCaptacao").val() != '')) {

        ValorCaptacaoCalc = (parseFloat($("#EdPercentCaptacao").val()) / 100) * parseFloat($('#EdValorVenda').val());
        ValorRepasseCalc2  = parseFloat($('#EdValorVenda').val()) - ValorCaptacaoCalc - ValorComissaoCalc;

        $("#EdValCaptacao").val(ValorCaptacaoCalc.toFixed(2).replace('.', ','))
        $("#EdValRepasseVenda").val(ValorRepasseCalc2.toFixed(2).replace('.', ','))
    }
});

$("#EdValorVenda").on('blur',function(){
    if ($("#EdValorVenda").val() != '') {
        ValorNum = parseFloat($("#EdValorVenda").val());
        // console.log(ValorNum.toFixed(2).replace('.', ','));
        $("#EdValorVenda").val(ValorNum.toFixed(2).replace('.', ','));
    }

    $("#EdPercentComissao").trigger("blur");
});
$("#EdValComissao").on('blur',function(){
    if ($("#EdValComissao").val() != '') {
        ValorNum = parseFloat($("#EdValComissao").val());
        // console.log(ValorNum.toFixed(2).replace('.', ','));
        $("#EdValComissao").val(ValorNum.toFixed(2).replace('.', ','));
    }

    $("#EdPercentComissao").trigger("blur");
});
$("#EdValRepasseVenda").on('blur',function(){
    if ($("#EdValRepasseVenda").val() != '') {
        ValorNum = parseFloat($("#EdValRepasseVenda").val());
        // console.log(ValorNum.toFixed(2).replace('.', ','));
        $("#EdValRepasseVenda").val(ValorNum.toFixed(2).replace('.', ','));
    }

    $("#EdPercentComissao").trigger("blur");
});
$("#EdPercentCaptacao").on('blur',function(){
    $("#EdPercentComissao").trigger("blur");
});
$("#EdValCaptacao").on('blur',function(){
    if ($("#EdValCaptacao").val() != '') {
        ValorNum = parseFloat($("#EdValCaptacao").val());
        // console.log(ValorNum.toFixed(2).replace('.', ','));
        $("#EdValCaptacao").val(ValorNum.toFixed(2).replace('.', ','));
    }

    $("#EdPercentComissao").trigger("blur");
});

//Imovel - Outros valores
$("#EdValorIPTU").on('blur',function(){
    if ($("#EdValorIPTU").val() != '') {
        ValorNum = parseFloat($("#EdValorIPTU").val());
        // console.log(ValorNum.toFixed(2).replace('.', ','));
        $("#EdValorIPTU").val(ValorNum.toFixed(2).replace('.', ','));
    }

});
$("#EdValorCondominio").on('blur',function(){
    if ($("#EdValorCondominio").val() != '') {
        ValorNum = parseFloat($("#EdValorCondominio").val());
        // console.log(ValorNum.toFixed(2).replace('.', ','));
        $("#EdValorCondominio").val(ValorNum.toFixed(2).replace('.', ','));
    }

});

//Adiciona pessoas
$(document).on('click', '#BtExcluirPessoa', function(){
    BotaoExcluir = $(this);

    $.SmartMessageBox({
        title : "Excluir pessoas relacionadas",
        content : "Você tem certeza que deseja excluir a pessoa vinculada " + BotaoExcluir.attr('nomepessoa') + " ?",
        buttons : '[Não][Sim]'
    }, function(ButtonPressed) {
        if (ButtonPressed === "Sim") {
            // console.log(BotaoExcluir.attr('nomepessoa'));
            // console.log(BotaoExcluir.attr('idvinculo'));

            request('onExcluirPessoaVinculada',{
                data: {idvinculo: BotaoExcluir.attr('idvinculo')},
                success: function(response){

                    if (response.resposta == 'ok')
                        $("#tr_"+BotaoExcluir.attr('idvinculo')).remove();
                },
            });
        }
        if (ButtonPressed === "Não") {

        }

    });
});

//Alugar imovel - Controle de imoveis
$("#EdNomePessoaAlugar").autocomplete({
    source : function(res, response) {

        request('onCarregaPessoas',{
            data : {
                featureClass : "P",
                style : "full",
                maxRows : 12,
                name_startsWith : res.term
            },
            success : function(data) {

                if (data[0] == undefined)   {
                    data[0] = data;
                    //console.log(data[0]);
                } else {

                }


                response($.map(data, function(item) {
                    // console.log(typeof item);

                    if (typeof item === 'object')
                        var teste = {id: item.id, label : item.label,value : item.value}
                    //console.log(item);

                    return teste;
                }));

            }
        });

    },
    minLength : 2,
    select : function(event, ui) {
        //console.log(ui);
        $("#EdIdPessoaAlugar").val(ui.item.id);
        $("#EdNomePessoaAlugar").val(ui.item.label);
    }
});


//Form add pessoas
$('form.form-modalAddPessoas').on('submit', function(e){
    e.preventDefault();

    if ($('#EdNomePessoaAddImovel').val() == '')    {
        $('#EdNomePessoaAddImovel').parent().addClass('has-error');
        return false;
    } else {
        $('#EdNomePessoaAddImovel').parent().removeClass('has-error');
    }

    if ($("#CbTipoVinculo option:selected").text() == '-- Tipo de vinculo --')    {
        $('#CbTipoVinculo').parent().addClass('has-error');
        return false;
    } else {
        $('#CbTipoVinculo').parent().removeClass('has-error');
    }

    if ($('#EdPercentual').val() == '')    {
        $('#EdPercentual').parent().addClass('has-error');
        return false;
    } else {
        $('#EdPercentual').parent().removeClass('has-error');
    }

    var data = new FormData(this);

    request('onFormModalAddPessoas',{
        data: data,
        success: function(response){
            // console.log(response.respostaAddPessoa);
            $('#myModalAddPessoas').modal('hide');

            var CorProprietario = '';
            if (response.respostaAddPessoa['NomeVinculo'] === 'Proprietario') {
                CorProprietario = 'txt-color-magenta';
                TextoNegrito    = 'negrito';
            }
            else {
                CorProprietario = '';
                TextoNegrito    = '';
            }

            $('#TbPessoasImovel').find(".sem-registro").remove();

            var LinhaTabela = '<tr class="row-data ' + CorProprietario + '">' +
                ' <td class="cell-link">' + response.respostaAddPessoa['EdNomePessoaAddImovel'] + '</td>' +
                ' <td class="cell-link">' + response.respostaAddPessoa['CpfCnpj'] + '</td>' +
                ' <td class="cell-link centralizado ' + TextoNegrito + '">' + response.respostaAddPessoa['NomeVinculo'] + '</td>' +
                ' <td class="cell-link centralizado">' + response.respostaAddPessoa['EdPercentual'] + '.000 %</td>' +
                '<td class="centralizado"><a href="#" idvinculo="' + response.respostaAddPessoa['Id'] + '" nomepessoa="' + response.respostaAddPessoa['EdNomePessoaAddImovel'] + '" class="btn btn-danger btn-xs" id="BtExcluirPessoa">Excluir</a></td>' +
                '</tr>';
            $('#TbPessoasImovel').append(LinhaTabela);
        },
        contentType: false,
        processData: false,
    });
});

//Form add caracteristica
$('form.form-modalAddCaracteristica').on('submit', function(e){
    e.preventDefault();

    var data = new FormData(this);

    request('onFormModalAddCaracteristica',{
        data: data,
        success: function(response){
            // console.log(response.respostaAddCaracteristica);
            $('#myModalAddCaracteristicas').modal('hide');

            $('#TbCaracteristicasAvancadas').find(".sem-registro").remove();

            var LinhaTabela = '<tr class="row-data">' +
                ' <td class="cell-link">' + response.respostaAddCaracteristica['Nome'] + '</td>' +
                ' <td class="cell-link">' + response.respostaAddCaracteristica['Descricao'] + '</td>' +
                ' <td class="cell-link">' + $.datepicker.formatDate('dd/mm/yy HH:mm', new Date(response.respostaAddCaracteristica['DataCriacao'])) + '</td>' +
                '</tr>';
            $('#TbCaracteristicasAvancadas').append(LinhaTabela);
        },
        contentType: false,
        processData: false,
    });
});


//Form add contrato aluguel
$('form.form-modalAddContratoAluguel').on('submit', function(e){
    // console.log('submit form');
    e.preventDefault();

    if ($('#EdIdPessoaAlugar').val() == '')    {
        $('#EdNomePessoaAlugar').parent().addClass('has-error');
        return false;
    } else {
        $('#EdNomePessoaAlugar').parent().removeClass('has-error');
    }

    if ($('#EdDataInicioContrato').val() == '')    {
        $('#EdDataInicioContrato').parent().addClass('has-error');
        return false;
    } else {
        $('#EdDataInicioContrato').parent().removeClass('has-error');
    }

    if ($('#EdDuracaoContrato').val() == '')    {
        $('#EdDuracaoContrato').parent().addClass('has-error');
        return false;
    } else {
        $('#EdDuracaoContrato').parent().removeClass('has-error');
    }

    if ($('#EdFimContrato').val() == '')    {
        $('#EdFimContrato').parent().addClass('has-error');
        return false;
    } else {
        $('#EdFimContrato').parent().removeClass('has-error');
    }

    if ($('#EdDiaVencimento').val() == '')    {
        $('#EdDiaVencimento').parent().addClass('has-error');
        return false;
    } else {
        $('#EdDiaVencimento').parent().removeClass('has-error');
    }

    var data = new FormData(this);

    request('onFormModalAlugarImovel',{
        data: data,
        success: function(response){
            // console.log(response.respostaAddAluguel);
            $('#myModalAlugarImovel').modal('hide');
            $('#BtNovoContratoAluguel').attr("disabled","disabled");
            $('#LabelSituacao').html('<span class="center-block padding-5 label label-primary">Alugado</span>');


            $('#TbContratosAluguel').find(".sem-registro").remove();

            if (response.respostaAddAluguel['Renovacao'] == '1')
                Renovacao = '<span class="label label-success">Sim</span>'
            else
                Renovacao = '<span class="label label-danger">Não</span>';

            if (response.respostaAddAluguel['Ativo'] == '1')
                Ativo = '<span class="label label-success">Sim</span>';
            else
                Ativo = '<span class="label label-danger">Não</span>';

            var LinhaTabela = '<tr class="row-data">' +
                ' <td class="cell-link centralizado">' + pad(response.respostaAddAluguel['NumeroContrato']['NumeroContrato'],4) + '</td>' +
                ' <td class="cell-link centralizado">' + $.datepicker.formatDate('dd/mm/yy', new Date(response.respostaAddAluguel['EdDataInicioContrato'])) + '</td>' +
                ' <td class="cell-link centralizado">' + $.datepicker.formatDate('dd/mm/yy', new Date(response.respostaAddAluguel['EdFimContrato'])) + '</td>' +
                ' <td class="cell-link centralizado">' + response.respostaAddAluguel['EdDuracaoContrato'] + '</td>' +
                ' <td class="cell-link centralizado">' + response.respostaAddAluguel['EdDiaVencimento'] + '</td>' +
                ' <td class="cell-link centralizado">' + Renovacao + '</td>' +
                ' <td class="cell-link centralizado"></td>' +
                ' <td class="cell-link centralizado">' + Ativo + '</td>' +
                ' <td class="cell-link centralizado">Agora</td>' +
                '</tr>';
            $('#TbContratosAluguel').append(LinhaTabela);
        },
        contentType: false,
        processData: false,
    });
});


function GraficoLigacoes(dados) {

    //Grafico por mes
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dados.LabelMes,
            datasets: [{
                label: 'Ligações por mês',
                data: dados.DataMes,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });

    //Grafico por midia
    var ctx = document.getElementById("myChart1").getContext('2d');
    var myChart1 = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: dados.LabelMidia,
            datasets: [{
                label: 'Ligações por tipo de mídia',
                data: dados.DataMidia,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255,99,132,1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        // options: {
        //     scales: {
        //         yAxes: [{
        //             ticks: {
        //                 //beginAtZero:true
        //             }
        //         }]
        //     }
        // }
    });

}


//Add contrato de venda
$("#EdNomePessoa").autocomplete({
    source : function(res, response) {
        /*$.ajax({
         url : "https://wsgi.dev/sistema/central-alugueis/CarregaPessoas",
         type: "POST",
         dataType : "jsonp",
         data : {
         featureClass : "P",
         style : "full",
         maxRows : 12,
         name_startsWith : request.term
         },
         success : function(data) {
         response($.map(data.geonames, function(item) {
         return {
         label : item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName,
         value : item.name
         }
         }));
         }
         });*/

        request('onCarregaPessoas',{
            data : {
                featureClass : "P",
                style : "full",
                maxRows : 12,
                name_startsWith : res.term
            },
            success : function(data) {

                if (data[0] == undefined)   {
                    data[0] = data;
                    //console.log(data[0]);
                } else {

                }


                response($.map(data, function(item) {
                    // console.log(typeof item);

                    if (typeof item === 'object')
                        var teste = {id: item.id, label : item.label,value : item.value}
                    //console.log(item);

                    return teste;
                }));

            }
        });

    },
    minLength : 2,
    select : function(event, ui) {
        //console.log(ui);
        $("#EdIdPessoa").val(ui.item.id);
        $("#EdNomePessoa").val(ui.item.label);
    }
});


$('button#BtAddPessoasVenda').on('click', function (e) {

    if ($("#CbTipoPartes option:selected").text() == '-- Papéis --')    {
        $('#CbTipoPartes').parent().addClass('has-error');
        return false;
    } else {
        $('#CbTipoPartes').parent().removeClass('has-error');
    }

    if ($('#EdNomePessoa').val() == '')    {
        $('#EdNomePessoa').parent().addClass('has-error');
        return false;
    } else {
        $('#EdNomePessoa').parent().removeClass('has-error');
    }

    Linha = '<div id="divPartes" class="row" style="background-color: whitesmoke; margin-left: 13px; margin-right: 13px; height: 30px; padding: 5px; margin-bottom: 7px; border: 1px solid #ddd;">' +
        '   <input type="hidden" name="EdTipoPartesCad[]" value="' + $('#CbTipoPartes').val() + '">' +
        '   <input type="hidden" name="EdIdPessoaCad[]" value="' + $('#EdIdPessoa').val() + '">' +
        '   <div class="col-xs-3" style="padding-left: 1px">' +
        '       <span class="label bg-color-green txt-color-white">' + $("#CbTipoPartes option:selected").text() + '</span>'+
        '   </div>' +
        '   <div class="col-xs-8"><i class="fa fa-user txt-color-blueDark"></i>&nbsp;<strong>' +
        $('#EdNomePessoa').val() +
        '   </strong></div>' +
        '<div class="col-xs-1"><button id="BtRemoveProposta" type="button" class="close" rel="tooltip" data-placement="right" data-original-title="Remover essa pessoa da proposta">×</button></div>'
    '</div>'

    $("#PessoasAdd").append(Linha);

    $('#EdIdPessoa').val("");
    $('#EdNomePessoa').val("");
    $('#CbTipoPartes').val("");
    $("select[id=CbTipoPartes]").val($("select[id=CbTipoPartes] option:first-child").val());

    $('#CbTipoPartes').focus();
});


$('button#BtAddParcelas').on('click', function (e) {

    if ($("#CbTipoPagamento option:selected").text() == '-- Tipo --')    {
        $('#CbTipoPagamento').parent().addClass('has-error');
        return false;
    } else {
        $('#CbTipoPagamento').parent().removeClass('has-error');
    }

    if ($('#EdValor').val() == '')    {
        $('#EdValor').parent().addClass('has-error');
        return false;
    } else {
        $('#EdValor').parent().removeClass('has-error');
    }

    if ($('#EdQntParcela').val() == '')    {
        $('#EdQntParcela').parent().addClass('has-error');
        return false;
    } else {
        $('#EdQntParcela').parent().removeClass('has-error');
    }

    if ($("#CbPeriodicidade option:selected").text() == '-- Periodicidade  --')    {
        $('#CbPeriodicidade').parent().addClass('has-error');
        return false;
    } else {
        $('#CbPeriodicidade').parent().removeClass('has-error');
    }

    if ($('#EdDataVencimento').val() == '')    {
        $('#EdDataVencimento').parent().addClass('has-error');
        return false;
    } else {
        $('#EdDataVencimento').parent().removeClass('has-error');
    }

    if ($("#CbAtulizacaoMonetaria option:selected").text() == '-- Atu. monentária --')    {
        $('#CbAtulizacaoMonetaria').parent().addClass('has-error');
        return false;
    } else {
        $('#CbAtulizacaoMonetaria').parent().removeClass('has-error');
    }

    if ($("#CbFormaPagamento option:selected").text() == '-- Forma de pagamento --')    {
        $('#CbFormaPagamento').parent().addClass('has-error');
        return false;
    } else {
        $('#CbFormaPagamento').parent().removeClass('has-error');
    }

    TempLabel = '';

    if ($("#CbTipoPagamento option:selected").text() == 'Entrada')
        TempLabel = 'bg-color-magenta txt-color-white';
    else
        TempLabel = 'bg-color-pinkDark txt-color-white';


    Linha = '<div id="divValores" class="row" style="background-color: whitesmoke; margin-left: 0px; margin-right: 0px;  height: 47px; padding: 5px; margin-bottom: 7px; border: 1px solid #ddd;">' +
        '   <input type="hidden" name="CbTipoPagamentoCad[]" value="' + $('#CbTipoPagamento').val() + '">' +
        '   <input type="hidden" name="EdValorCad[]" value="' + $('#EdValor').val() + '">' +
        '   <input type="hidden" name="EdParcelasCad[]" value="' + $('#EdQntParcela').val() + '">' +
        '   <input type="hidden" name="CbPeriodicidadeCad[]" value="' + $('#CbPeriodicidade').val() + '">' +
        '   <input type="hidden" name="EdDataVencimentoCad[]" value="' + $('#EdDataVencimento').val() + '">' +
        '   <input type="hidden" name="CbAtulizacaoMonetariaCad[]" value="' + $('#CbAtulizacaoMonetaria').val() + '">' +
        '   <input type="hidden" name="CbFormaPagamentoCad[]" value="' + $('#CbFormaPagamento').val() + '">' +
        '   <div class="col-xs-2" style="padding-left: 1px">' +
        '       <span class="label ' + TempLabel + '">' + $("#CbTipoPagamento option:selected").text() + '</span>'+
        '   </div>' +
        '   <div class="col-xs-9">' +
        '       <i class="fa fa-dollar txt-color-blueDark"></i>&nbsp; (' + $("#CbPeriodicidade option:selected").text() + ') <strong> R$ ' + $('#EdValor').val() + ',00 dividido em ' + $('#EdQntParcela').val() +' parcela(s)</strong><br>' +
        '       <i class="fa fa-refresh txt-color-blueDark"></i>&nbsp;' + $("#CbAtulizacaoMonetaria option:selected").text() + ' pagas em ' + $("#CbFormaPagamento option:selected").text() +
        '   </div>' +
        '<div class="col-xs-1"><button id="BtRemoveProposta" type="button" class="close" rel="tooltip" data-placement="right" data-original-title="Remover essa pessoa da proposta">×</button></div>'
    '</div>'

    $("#ValoresAdd").append(Linha);

    $('#EdValor').val("");
    $('#EdQntParcela').val("");
    $('#EdDataVencimento').val("");
    $("select[id=CbTipoPagamento]").val($("select[id=CbTipoPagamento] option:first-child").val());
    $("select[id=CbPeriodicidade]").val($("select[id=CbPeriodicidade] option:first-child").val());
    $("select[id=CbAtulizacaoMonetaria]").val($("select[id=CbAtulizacaoMonetaria] option:first-child").val());
    $("select[id=CbFormaPagamento]").val($("select[id=CbFormaPagamento] option:first-child").val());

    $('#CbTipoPagamento').focus();
});

$(document).on('click', 'button#BtRemoveProposta', function(){
    $(this).parent().parent().parent().html("")
});

//Form add contrato de venda
$('form.form-modalAddContratoVenda').on('submit', function(e){
    // console.log('submit form');
    e.preventDefault();

    if ($('div#divPartes').length == 0) {
        $("#MensagemAddPessoas").fadeIn();
        return false;
    } else {
        $("#MensagemAddPessoas").fadeOut();
    }


    if ($('div#divValores').length == 0) {
        $("#MensagemAddPartes").fadeIn();
        return false;
    } else {
        $("#MensagemAddPartes").fadeOut();
    }


    var data = new FormData(this);

    request('onFormModalVendeImovel',{
        data: data,
        success: function(response){

            if (response.respostaAddContratoVenda == 'ok')  {
                $('#myModalAddContratoVenda').modal('hide');

                $('#TbPropostasVenda').find(".sem-registro").remove();

                if (response.Propostas.length == undefined) {
                    $('#TbPropostasVenda').append(LinhaTabela);

                } else {
                    var LinhaTabela = '';
                    var Padrao = '';

                    for(var i=0; i < response.Propostas.length; i++) {

                        Color = '';
                        if (response.Propostas[i]['FormaPagamentoEntrada'] == 'CHEQUE')
                            Color = 'bg-color-greenDark';
                        else if (response.Propostas[i]['FormaPagamentoEntrada'] == 'DINHEIRO')
                            Color = 'bg-color-blueDark';
                        else if (response.Propostas[i]['FormaPagamentoEntrada'] == 'BOLETO')
                            Color = 'bg-color-orangeDark';
                        else if (response.Propostas[i]['FormaPagamentoEntrada'] == 'DEBITO_CONTA')
                            Color = 'bg-color-teal';
                        else if (response.Propostas[i]['FormaPagamentoEntrada'] == 'FINANCIAMENTO')
                            Color = 'bg-color-pinkDark';
                        else if (response.Propostas[i]['FormaPagamentoEntrada'] == 'NOTA_PROMISSORIA')
                            Color = 'bg-color-teal';


                        ValorEntrada = parseFloat(response.Propostas[i]['ValorEntrada']);
                        ValorFinanciamento = parseFloat(response.Propostas[i]['ValorFinanciado']);





                        LinhaTabela += '<tr class="row-data">' +
                            '     <td><a rel="tooltip" data-placement="right" data-original-title="<i class=\'fa fa-info-circle text-primary\'></i> Clique aqui para abrir os detalhes dessa proposta" data-html="true" target="_blank" href="/sistema/central-vendas/editar/' + response.Propostas[i]['Id'] + '">' + response.Propostas[i]['Nome'] + '</a></td>' +
                            '     <td><span class="center-block padding-5 label label-primary">Aberta</span></td>' +
                            '     <td class=""><span class="center-block padding-5 label ' + Color + ' txt-color-white">' + Initcap(response.Propostas[i]['FormaPagamentoEntrada'],'_') + '</span></td>' +
                            '     <td class="centralizado">' + $.datepicker.formatDate('dd/mm/yy', new Date(response.Propostas[i]['DataValidade'])) + '</td>' +
                            '     <td class="centralizado">' + $.datepicker.formatDate('dd/mm/yy', new Date(response.Propostas[i]['DataPossivelEntrada'])) + '</td>' +
                            '     <td class="centralizado"><span class="badge bg-color-magenta">' + pad(response.Propostas[i]['NumParcelasEntrada'],3) + '</span></td>' +
                            '     <td class="centralizado"><span class="badge bg-color-purple">' + pad(response.Propostas[i]['NumParcelasFinanciamento'],3) + '</span></td>' +
                            '     <td class="">' + ValorEntrada.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) + '</td>' +
                            '     <td class="">' + ValorFinanciamento.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) + '</td>' +
                            '     <td class="centralizado">' + $.datepicker.formatDate('dd/mm/yy', new Date(response.Propostas[i]['DataCriacao'])) + '</td>' +
                            '</tr>';
                    }
                    $('#TbPropostasVenda tbody').html(LinhaTabela);
                }
            }


        },
        contentType: false,
        processData: false,
    });

});






var map;

//Gmaps
function initMap() {
    var myLatlng = new google.maps.LatLng(-16.6869,-49.2648);

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 17,
        center: myLatlng,
        mapTypeId: 'satellite'

    });
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map
    });
}





$(window).load(function() {
    google.maps.event.trigger(map, 'resize');
});

$(document).ready(function() {

    //Carrega os graficos
    if ($("#EdIdImovel").val() == undefined)
        return false;
    else {
        request('onGraficoLigacoes', {
            data: {
                EdImovel: $("#EdIdImovel").val()
            },
            success: function (response) {
                // console.log(response);
                GraficoLigacoes(response);
            }
        });
    }

    //Inicializa o map
    initMap();

    //Inicializa os anexos
    $("#lightgallery").lightGallery();

});



Dropzone.autoDiscover = false;

$("div#mydropzoneImoveis").dropzone({
    url: "/sistema/imoveis/postimg",
    addRemoveLinks : true,
    maxFilesize: 10.5,
    dictDefaultMessage: '<span class="text-center"><span class="font-lg visible-xs-block visible-sm-block visible-lg-block"><span class="font-lg"><i class="fa fa-caret-right text-danger"></i> Arraste e solte os arquivos aqui para enviar<span class="font-xs"></span></span><span>&nbsp&nbsp<h4 class="display-inline"> (ou clique)</h4></span>',
    dictResponseError: 'Error uploading file!',
    dictRemoveFile: 'Remover arquivo',
    dictCancelUpload: 'Cancelar upload',
    init: function() {
        this.on("sending", function(file, xhr, formData){
            formData.append("ImovelId", $("#ImovelId").val());
        });
    },
    success: function (file, response) {
        var imgName = response;
        file.previewElement.classList.add("dz-success");
        console.log("Successfully uploaded :" + imgName);
    },
    error: function (file, response) {
        file.previewElement.classList.add("dz-error");
        console.log(file);
        console.log(response);
    }
});