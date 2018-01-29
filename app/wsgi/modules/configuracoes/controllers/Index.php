<?php namespace wSGI\Modules\Configuracoes\Controllers;

use Core\Controller;
use wSGI\Modules\Configuracoes\Models\BoletoBanco;

class Index extends Controller
{
    public $model = 'Configuracao';

    public $pageTitle = 'Configurações';

    public function index() {
        $this->action = 'Listagem';
        parent::index();
    }


//Eventos

    public function onFormModalAddConta()   {
        // Dados Enviados pelo formulário
        $Data = post();

        if ($Data['EdIdConta'] == '') { //Novo registro
            $Conta = new BoletoBanco();
            $Conta->BancoNumero           = $Data['EdNumBanco'];
            $Conta->BancoNumeroDigito     = $Data['EdDvBanco'];
            $Conta->BancoNome             = $Data['EdNomeBanco'];
            $Conta->AgenciaNumero         = $Data['EdAgencia'];
            $Conta->AgenciaNumeroDigito   = $Data['EdAgenciaDV'];
            $Conta->AgenciaNome           = $Data['EdNomeAgencia'];
            $Conta->AgenciaCidade         = $Data['EdCidadeAgencia'];
            $Conta->AgenciaUf             = $Data['EdUfAgencia'];
            $Conta->ContaNumero           = $Data['EdNumConta'];
            $Conta->ContaDigito           = $Data['EdDvConta'];
            $Conta->CedenteCodigo         = $Data['EdCodCedente'];
            $Conta->CedenteNome           = $Data['EdNomeCedente'];
            $Conta->CedenteCnpj           = $Data['EdCnpjCedente'];
            $Conta->CedenteConvenio       = $Data['EdConvenio'];
            $Conta->NossoNumero           = $Data['EdNossoNumIni'];
            $Conta->NossoNumeroUltimo     = $Data['EdNossoNumFim'];
            $Conta->Carteira              = $Data['EdCarteira'];
            $Conta->Modalidade            = $Data['EdModalidade'];
            $Conta->MsgLocalPagamento     = $Data['EdTextoLocalPg'];
            $Conta->TarifaBoleto          = $Data['EdCusto'];

            if (isset($Data['CkPadrao']))   {
                $Conta->Padrao                = 'S';
            } else {
                $Conta->Padrao                = 'N';
            }

//      $Conta->LayoutRemessa         = $Data['EdIdImovel'];
//      $Conta->MultaPadraoPercentual = $Data['EdIdImovel'];
//      $Conta->JurosPadrao           = $Data['EdIdImovel'];
//      $Conta->DiasAposVencimento    = $Data['EdIdImovel'];
            $ContaId = $Conta->save();

            if ($Conta->Padrao == 'S')  {
                $DataRetorno = $this->model->rawQuery("update BoletoBancos set Padrao = 'N' where Id <> '" . $ContaId . "'");
            }
        } else {
            $Conta = BoletoBanco::find($Data['EdIdConta']);
            $Conta->BancoNumero           = $Data['EdNumBanco'];
            $Conta->BancoNumeroDigito     = $Data['EdDvBanco'];
            $Conta->BancoNome             = $Data['EdNomeBanco'];
            $Conta->AgenciaNumero         = $Data['EdAgencia'];
            $Conta->AgenciaNumeroDigito   = $Data['EdAgenciaDV'];
            $Conta->AgenciaNome           = $Data['EdNomeAgencia'];
            $Conta->AgenciaCidade         = $Data['EdCidadeAgencia'];
            $Conta->AgenciaUf             = $Data['EdUfAgencia'];
            $Conta->ContaNumero           = $Data['EdNumConta'];
            $Conta->ContaDigito           = $Data['EdDvConta'];
            $Conta->CedenteCodigo         = $Data['EdCodCedente'];
            $Conta->CedenteNome           = $Data['EdNomeCedente'];
            $Conta->CedenteCnpj           = $Data['EdCnpjCedente'];
            $Conta->CedenteConvenio       = $Data['EdConvenio'];
            $Conta->NossoNumero           = $Data['EdNossoNumIni'];
            $Conta->NossoNumeroUltimo     = $Data['EdNossoNumFim'];
            $Conta->Carteira              = $Data['EdCarteira'];
            $Conta->Modalidade            = $Data['EdModalidade'];
            $Conta->MsgLocalPagamento     = $Data['EdTextoLocalPg'];
            $Conta->TarifaBoleto          = $Data['EdCusto'];

            if (isset($Data['CkPadrao']))   {
                $Conta->Padrao                = 'S';
            } else {
                $Conta->Padrao                = 'N';
            }

//      $Conta->LayoutRemessa         = $Data['EdIdImovel'];
//      $Conta->MultaPadraoPercentual = $Data['EdIdImovel'];
//      $Conta->JurosPadrao           = $Data['EdIdImovel'];
//      $Conta->DiasAposVencimento    = $Data['EdIdImovel'];
            $ContaId = $Conta->save();

            if ($Conta->Padrao == 'S')  {
                $DataRetorno = $this->model->rawQuery("update BoletoBancos set Padrao = 'N' where Id <> '" . $Data['EdIdConta'] . "'");
            }
        }



        $Data = BoletoBanco::all();



        return  ['respostaAddConta' => $Data ];
    }

    public function onExcluirConta()    {
        $Conta = BoletoBanco::find(post('idconta'));
        $Conta->delete();

        return ['resposta' => 'ok'];
    }

    public function onCarregaConta()    {
        return ['resposta' => BoletoBanco::find(post('idconta'))];
    }


    public function onRun(): void    {
        $this->addScript('assets/js/configuracoes.js');
    }



}