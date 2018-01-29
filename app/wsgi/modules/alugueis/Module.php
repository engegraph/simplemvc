<?php namespace wSGI\Modules\Alugueis;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    /**
     * @return array
     */
    public function registerNavigation() : array
    {
        return [
            'alugueis' => [
                'url' => 'javascript:void(0);',
                'label' => 'Aluguel',
                'icon' => 'suitcase',
                'permissions' => ['*'],
                'childs' => [
                    [
                        'url' => backend_url('/alugueis/central'),
                        'label' => 'Central de aluguéis',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => 'javascript:void(0);',
                        'label' => 'Controle gerais',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/alugueis/controle-chaves'),
                                'label' => 'Controle de chaves',
                                'permissions' => ['*'],
                            ],                [
                                'url' => backend_url('/alugueis/vistorias'),
                                'label' => 'Central de vistorias',
                                'permissions' => ['*'],
                            ]
                        ]
                    ],
                    [
                        'url' => 'javascript:void(0);',
                        'label' => 'Recibos',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/alugueis/recibo-locador'),
                                'label' => 'Recibo locador',
                                'permissions' => ['*'],
                            ],                [
                                'url' => backend_url('/alugueis/recibo-locatario'),
                                'label' => 'Recibo locatário',
                                'permissions' => ['*'],
                            ]
                        ]
                    ],
                    [
                        'url' => backend_url('/alugueis/lancamentos'),
                        'label' => 'Lançamentos',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/alugueis/pagamentos'),
                        'label' => 'Pagamento de aluguéis',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/alugueis/inadimplentes'),
                        'label' => 'Lista de inadimplmentes',
                        'permissions' => ['*'],
                    ]
                ],
            ]
        ];
    }
}