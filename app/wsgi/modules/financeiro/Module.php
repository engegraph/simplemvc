<?php namespace wSGI\Modules\Financeiro;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{

    public function registerNavigation(): array
    {
        return [
            'financeiro' => [
                'url' => 'javascript:void(0);',
                'label' => 'Financeiro',
                'icon' => 'dollar',
                'permissions' => ['*'],
                'childs' => [
                    [
                        'url' => backend_url('/financeiro/caixa'),
                        'label' => 'caixa',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/financeiro/balancete'),
                        'label' => 'Balancete',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/financeiro/contas-pagar'),
                        'label' => 'Contas a pagar',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/financeiro/contas-receber'),
                        'label' => 'Contas a receber',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/financeiro/receitas-simplificado'),
                        'label' => 'Receitas simplificado',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/financeiro/despesas-simplificado'),
                        'label' => 'Despesas simplificado',
                        'permissions' => ['*'],
                    ]
                ],
            ]
        ];
    }
}