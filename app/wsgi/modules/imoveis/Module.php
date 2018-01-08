<?php namespace wSGI\Modules\Imoveis;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'imoveis' => [
                'url' => backend_url('/imoveis'),
                'label' => 'Imóveis',
                'icon' => 'home',
            ]
        ];
    }
}