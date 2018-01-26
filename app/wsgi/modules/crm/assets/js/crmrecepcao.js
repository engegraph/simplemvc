/**
 * Created by rodri on 22/12/2017.
 */

$("#EdNomeCliente").on('blur',function(){
    request('onImovelVerificaPessoaCadastrada',{
        data: {NomePessoa: $('#EdNomeCliente').val()},
        success: function(response){

            //Verifica se retornou erro que o imovel nao existe
            if (response['Pessoa']['erro']) {
                $("#CkClienteCadastrado").prop('checked', false);
            } else {
                $("#EdPessoaId").val(response['Pessoa']['Id']);
                $("#CkClienteCadastrado").prop('checked', true);

            }
        }
    });
});

$("#EdPastaNumSite").on('blur',function(){
    request('onImovelVerificaImovelCadastrado',{
        data: {PastaNumSite: $('#EdPastaNumSite').val()},
        success: function(response){

            //Verifica se retornou erro que o imovel nao existe
            if (response['Imovel']['erro']) {
                $("#CkImovelCadastrado").prop('checked', false);
            } else {
                $("#EdImovelId").val(response['Imovel']['Id']);
                $("#CkImovelCadastrado").prop('checked', true);

            }
        }
    });
});