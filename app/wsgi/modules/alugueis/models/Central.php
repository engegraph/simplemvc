<?php namespace wSGI\Modules\Alugueis\Models;

use Core\Model;
use App\Modules\wSGI\Models\Imovel;
use App\Modules\wSGI\Models\ImovelContratoParcelas;

class Central  extends Model
{

    public $table = 'ImovelContratos';

    public function ImoveisDisponiveis()
    {
        return Imovel::where('DisponivelAluguel','=',1)->
                       where('Situacao','=','Livre')->get();
    }

    public function save(array $options = [])
    {

        //Insere na tabela ImovelContratos
        $retorno = parent::save($options);
        $this->rawQuery("update ImovelContratos set NumeroContrato = (select MAX(COALESCE(NumeroContrato, 0)) + 1 from ImovelContratos) where Id = '" . $retorno . "'");

        //Muda a situação do Imovel
        imovel::where('Id',Post('CentralAluguei.ImovelId'))->update(['Situacao' => 'Alugado']);

        //Localiza os dados do imovel
        $Imovel = imovel::find(Post('CentralAluguei.ImovelId'));

        //Insere na tabela vinculos
        $Vinculo = new VinculoPessoaImovel();
        $Vinculo->ImovelId       = $Imovel->Id;
        $Vinculo->PessoaId       = Post('CentralAluguei.PessoaId');
        $Vinculo->TipoVinculoId  = '{00000001-0000-0000-0000-000000000000}'; //Locatario
        $Vinculo->Percentual     = 100;
        $Vinculo->Ativo          = 1;
        $Vinculo->save();

        //Insere as parcelas
        for ($i = 1; $i <= intval(Post('CentralAluguei.TempoContrato')); $i++) {

            $ArrayDataVencimento   = explode('-', Post('CentralAluguei.DataInicio'));
            $DataVencimentoBase    = $ArrayDataVencimento[0] . '-' . $ArrayDataVencimento[1] . '-' . Post('CentralAluguei.DiaVencimento');
            $DataVencimentoEfetiva = month_add($DataVencimentoBase,$i-1);
            $DataVencimentoEfetiva = next_util_day($DataVencimentoEfetiva);
            $DataVencimentoEfetivaArray = explode('-', $DataVencimentoEfetiva);

            $Parcela = new ImovelContratoParcelas();
            $Parcela->ImovelContratoId = $retorno;
            $Parcela->NumeroParcela    = $i;
            $Parcela->DiaReferencia    = $DataVencimentoEfetivaArray[2];
            $Parcela->MesReferencia    = $DataVencimentoEfetivaArray[1];
            $Parcela->AnoReferencia    = $DataVencimentoEfetivaArray[0];
            $Parcela->DataVencimento   = $DataVencimentoEfetiva;
            $Parcela->Pago             = 0;
            $Parcela->Vencido          = 0;
            $Parcela->Cancelado        = 0;
            $Parcela->Valor            = $Imovel->ValorAluguel;
            $Parcela->save();
        }
        return $retorno;

    }
}