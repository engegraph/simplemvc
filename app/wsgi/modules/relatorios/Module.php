<?php namespace wSGI\Modules\Relatorios;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{

    public function registerNavigation(): array
    {
        return [
            'relatorios' => [
                'url' => 'javascript:void(0);',
                'label' => 'Relatórios',
                'icon' => 'print',
                'permissions' => ['*'],
                'childs' => [
                    [
                        'url' => backend_url('/graficos'),
                        'label' => 'Gráficos',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/rel-caixa'),
                        'label' => 'caixa',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => 'javascript:void(0);',
                        'label' => 'Plano de contas',
                        'permissions' => ['*'],
                        'childs' =>[
                            [
                                'url' => backend_url('/rel-despesas-simplificado'),
                                'label' => 'Despesas simplificado',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-receitas-simplificado'),
                                'label' => 'Receitas simplificado',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-grafico-comparativo'),
                                'label' => 'Gráfico comparativo',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-contas'),
                                'label' => 'Contas',
                                'permissions' => ['*'],
                            ]
                        ]
                    ],
                    [
                        'url' => 'javascript:void(0);',
                        'label' => 'Aluguéis',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/rel-alugueis-vencidos'),
                                'label' => 'Aluguéis vencidos',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-alugueis-vencer'),
                                'label' => 'Aluguéis à vencer',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-repasse-pendente'),
                                'label' => 'Repasses pendentes',
                                'permissions' => ['*'],
                            ]
                        ]
                    ],
                    [
                        'url' => 'javascript:void(0);',
                        'label' => 'Vendas',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/rel-parcelas'),
                                'label' => 'Parcelas',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-transf-proprietario'),
                                'label' => 'Transferência de proprietário',
                                'permissions' => ['*'],
                            ]
                        ]
                    ],
                    [
                        'url' => 'javascript:void(0);',
                        'label' => 'Imóveis',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/RelImoveisListagem'),
                                'label' => 'Listagem',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-imovel-divulgacao'),
                                'label' => 'Divulgações',
                                'permissions' => ['*'],
                            ],
                        ]
                    ],
                    [
                        'url' => 'javascript:void(0);',
                        'label' => 'Locadores/Proprietários',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/rel-locadores-listagem'),
                                'label' => 'Listagem',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-repasse-por-proprietario'),
                                'label' => 'Repasse por proprietário',
                                'permissions' => ['*'],
                            ],
                        ]
                    ],
                    [
                        'url' => 'javascript:void(0);',
                        'label' => 'Locatários/Compradores',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/rel-locatario-listagem'),
                                'label' => 'Listagem',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-locatario-ficha'),
                                'label' => 'Ficha',
                                'permissions' => ['*'],
                            ],
                        ]
                    ],
                    [
                        'url' => 'javascript:void(0);',
                        'label' => 'Outros',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/rel-corretores'),
                                'label' => 'Corretores',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-fiadores'),
                                'label' => 'Fiadores/Avalistas',
                                'permissions' => ['*'],
                            ],[
                                'url' => backend_url('/rel-Procuradores'),
                                'label' => 'Procuradores',
                                'permissions' => ['*'],
                            ],
                        ]
                    ],
                ],
            ]
        ];
    }
}