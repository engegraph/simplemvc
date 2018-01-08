<?php namespace wSGI\Modules\Aluguel;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    /**
     * @return array
     */
    public function registerNavigation() : array
    {
        return [
            'aluguel' => [
                'label'       => 'Aluguel',
                'url'         => 'aluguel/controle-de-chaves',
                'icon'        => 'icon-pencil',
                'order' => 2,
                'childs' => [
                    [
                        'url' => backend_url('/central-alugueis'),
                        'label' => 'Central de aluguéis',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => '#',
                        'label' => 'Controle gerais',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/imovel-controle-chaves'),
                                'label' => 'Controle de chaves',
                                'permissions' => ['*'],
                            ],                [
                                'url' => backend_url('/imovel-controle-vistorias'),
                                'label' => 'Central de vistorias',
                                'permissions' => ['*'],
                            ]
                        ]
                    ],
                    [
                        'url' => '#',
                        'label' => 'Recibos',
                        'permissions' => ['*'],
                        'childs' => [
                            [
                                'url' => backend_url('/recibo-locador'),
                                'label' => 'Recibo locador',
                                'permissions' => ['*'],
                            ],                [
                                'url' => backend_url('/recibo-locatario'),
                                'label' => 'Recibo locatário',
                                'permissions' => ['*'],
                            ]
                        ]
                    ],
                    [
                        'url' => backend_url('/imovel-lancamento'),
                        'label' => 'Lançamentos',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/pagamento-aluguel'),
                        'label' => 'Pagamento de aluguéis',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/lista-inadimplente'),
                        'label' => 'Lista de inadimplmentes',
                        'permissions' => ['*'],
                    ]
                ],
            ]
        ];
    }
}