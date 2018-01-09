<?php namespace wSGI\Modules\Controles;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'controles' => [
                'url' => 'javascript:void(0);',
                'label' => 'Controles',
                'icon' => 'list',
                'permissions' => ['*'],
                'childs' => [
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Categoria de anexos',
                        'attributes'  => ['data-controle'=>'AnexoCategoria'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'attributes'  => ['data-controle'=>'Cidade'],
                        'label'       => 'Cidades',
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Categoria de contratos',
                        'attributes'  => ['data-controle'=>'ContratoCategoria'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Concessionárias de serviços',
                        'attributes'  => ['data-controle'=>'ConcessionariaServico'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Condição de pagamento',
                        'attributes'  => ['data-controle'=>'CondicaoPagamento'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Características dos imóveis',
                        'attributes'  => ['data-controle'=>'CaracteristicasImovel'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Estado Civil',
                        'attributes'  => ['data-controle'=>'EstadoCivil'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'attributes'  => ['data-controle'=>'Estado'],
                        'label'       => 'Federações',
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Finalidade do imóvel',
                        'attributes'  => ['data-controle'=>'FinalidadeImovel'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Naturalidades',
                        'attributes'  => ['data-controle'=>'Naturalidade'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Nacionalidades',
                        'attributes'  => ['modal'=>'Nacionalidade'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Profissões',
                        'attributes'  => ['data-controle'=>'Profissao'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipos Distrato',
                        'attributes'  => ['data-controle'=>'TipoDistrato'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo do imóvel',
                        'attributes'  => ['data-controle'=>'TipoImovel'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo de laje',
                        'attributes'  => ['data-controle'=>'TipoLaje'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo de vinculo(pessoa/imóvel)',
                        'attributes'  => ['data-controle'=>'TipoVinculo'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo de mídia',
                        'attributes'  => ['data-controle'=>'TipoMidia'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo documento',
                        'attributes'  => ['data-controle'=>'TipoDocumento'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo vistoria',
                        'attributes'  => ['data-controle'=>'TipoVistoria'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo manutenção',
                        'attributes'  => ['data-controle'=>'TipoManutencao'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo lançamento financeiro',
                        'attributes'  => ['data-controle'=>'TipoLancamentoFinanceiro'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo de entrada no caixa',
                        'attributes'  => ['data-controle'=>'TipoEntradaCaixa'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo de contas a pagar',
                        'attributes'  => ['data-controle'=>'TipoContaPagar'],
                        'permissions' => ['*'],
                    ],
                    [
                        'url'         => 'javascript:void(0);',
                        'label'       => 'Tipo de grupo de contas a pagar',
                        'attributes'  => ['data-controle'=>'TipoContaGrupo'],
                        'permissions' => ['*'],
                    ],
                ]
            ]
        ];
    }
}