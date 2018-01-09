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
                        'url' => backend_url('/alugueis/central-alugueis'),
                        'label' => 'Central de aluguéis',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => 'javascript:void(0);',
                        'label' => 'Controle gerais',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/alugueis/imovel-controle-chaves'),
                                'label' => 'Controle de chaves',
                                'permissions' => ['*'],
                            ],                [
                                'url' => backend_url('/alugueis/imovel-controle-vistorias'),
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
                        'url' => backend_url('/alugueis/imovel-lancamento'),
                        'label' => 'Lançamentos',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/alugueis/pagamento-aluguel'),
                        'label' => 'Pagamento de aluguéis',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/alugueis/lista-inadimplente'),
                        'label' => 'Lista de inadimplmentes',
                        'permissions' => ['*'],
                    ]
                ],
            ]
        ];
    }
}