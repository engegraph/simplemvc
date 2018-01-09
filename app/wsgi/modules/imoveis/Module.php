<?php namespace wSGI\Modules\Imoveis;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'imoveis' => [
                'url' => 'javascript:void(0)',
                'label' => 'Imóveis',
                'permissions' => ['*'],
                'icon' => 'home',
                'childs' => [
                    [
                        'url' => backend_url('/imoveis/RelImoveisListagem'),
                        'label' => 'Listagem',
                        'permissions' => ['*'],
                    ],[
                        'url' => backend_url('/imoveis/rel-imovel-divulgacao'),
                        'label' => 'Divulgações',
                        'permissions' => ['*'],
                    ],
                ]
            ]
        ];
    }
}