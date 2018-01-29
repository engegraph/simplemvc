<?php namespace wSGI\Modules\Vendas\Models;

use Core\Model;
use App\Modules\wSGI\Models\Imovel;
use App\Modules\wSGI\Models\ImovelContratoParcelas;
use App\Modules\wSGI\Models\VinculoPessoaProposta;
use App\Modules\wSGI\Models\Proposta;
use App\Modules\wSGI\Models\PropostaValores;
use App\Modules\wSGI\Models\PropostaValorParcelas;
use Webpatser\Uuid\Uuid;


class Central  extends Model
{

    public $table = 'Propostas';

    protected $rules = [];
//    protected $model = 'Propostas';

    public function ImoveisDisponiveis()
    {
        return Imovel::where('DisponivelVenda','=',1)->
                       whereIn('Situacao', ['Livre', 'Reservado', 'Negociação'])->get();

    }

    /*public function save(array $options = [])
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

    }*/

//Relacionamentos
    public function Imovel()    {
        return $this->hasOne('App\Modules\wSGI\Models\Imovel','Id', 'ImovelId');
    }

    public function Vinculos()    {
        return $this->hasMany('App\Modules\wSGI\Models\VinculoPessoaProposta','PropostaId', 'Id');
    }

    public function Valores()    {
        return $this->hasMany('App\Modules\wSGI\Models\PropostaValores','PropostaId', 'Id');
    }

    public function StatusProposta()
    {
        return $this->hasOne('App\Modules\wSGI\Models\TipoStatusProposta','Id', 'StatusPropostaId');
    }

    //Tabelas basicas
    public function StatusPropostas()
    {
        return $this->hasAll('App\Modules\wSGI\Models\TipoStatusProposta');
    }

    public function TiposParteProposta()
    {
        return $this->hasAll('App\Modules\wSGI\Models\TipoParteProposta');
    }

    public function TiposPagamento()
    {
        return $this->hasAll('App\Modules\wSGI\Models\TipoPagamento');
//        return [];
    }

    public function onBeforeSave()  {

        //Verifica se teve alteração de status
        $Proposta = Proposta::find($this->Id);

        //Teve alteração do status da proposta
        if ($Proposta->StatusPropostaId != $this->StatusPropostaId) {

            //Proposta foi aceita, gera as parcelas
            if ($this->StatusPropostaId = '00000003-0000-0000-0000-000000000000') {

                $PropostaValores = PropostaValores::where('PropostaId',$this->Id)->get();


                try
                {
                    $PropostaValores->each(function(PropostaValores $Valores) use ($Proposta){

                        /*                    $res = $Valores->toArray();
                                            var_dump($res);*/

                        $Contador = 0;
                        $DataVencimento = null;
                        while ($Contador < $Valores->QtdParcelas)
                        {
                            $Contador++;
                            if(!$DataVencimento)
                                $DataVencimento = $Valores->DataVencimento;
                            else {
                                $DataVencimento = month_add($Valores->DataVencimento,$Contador-1);
                                $DataVencimento = next_util_day($DataVencimento);
                            }

                            $Pvp = new PropostaValorParcelas;

                            $data = [
                                'DataVencimento'  => $DataVencimento,
                                'NumeroParcela'   => $Contador,
                                'Sequencia'       => $Valores->QtdParcelas,
                                'Valor'           => (float)$Valores->ValorParcela,
                                'ValorMulta'      => 0,
                                'DiasAtraso'      => 0,
                                'ValorPago'       => 0,
                                'ValorMora'       => 0,
                                'Pago'            => 0,
                                'Vencido'         => 0,
                                'Cancelado'       => 0,
                                'PropostaId'      => $Proposta->Id,
                                'PropostaValorId' => $Valores->Id
                            ];


                            foreach ($data as $k => $v)
                                $Pvp->$k = $v;

                            $Pvp->save();

                        }
                    });
                }
                catch (\Exception $e)
                {
                    die($e->getMessage());
                }

                //Atualiza a situação do Imovel e o status de vendido
                $Imovel = Imovel::find($this->ImovelId);
                $Imovel->Situacao = 'Vendido';
                $Imovel->Vendido  = 1;
                $Imovel->save();

                Proposta::where('ImovelId','=', $this->ImovelId)->
                    update(['StatusPropostaId' => '{00000002-0000-0000-0000-000000000000}']);


                //Recusa todas as outras propostas desse imovel


            }
        }
    }

}