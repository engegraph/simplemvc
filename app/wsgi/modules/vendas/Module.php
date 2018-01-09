<?php
/**
 * Created by PhpStorm.
 * User: airton.lopes
 * Date: 09/01/2018
 * Time: 11:33
 */

namespace wSGI\Modules\Vendas;


use Core\Providers\ModuleBase;

class Module extends ModuleBase
{

    public function registerNavigation(): array
    {
        return [
            'vendas' => [
                'url' => 'javascript:void(0);',
                'label' => 'Vendas',
                'icon' => 'legal',
                'permissions' => ['*'],
                'childs' => [
                    [
                        'url' => backend_url('/vendas/central'),
                        'label' => 'Central de vendas',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/vendas/controle-parcelas'),
                        'label' => 'Controle de parcelas',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/vendas/simulador-financiamento'),
                        'label' => 'Simulador de financiamento',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/vendas/transferencia-contrato'),
                        'label' => 'Transferência de contratos',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/vendas/autorizacao-escritura'),
                        'label' => 'Autorização de escritura',
                        'permissions' => ['*'],
                    ]
                ],
            ]
        ];
    }
}