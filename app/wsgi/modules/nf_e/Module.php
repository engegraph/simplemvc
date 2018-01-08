<?php namespace wSGI\Modules\NF_e;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'nf-e' => [
                'url' => backend_url('/nf-e'),
                'label' => 'Nota Fiscal Eletrônica',
                'icon'  => 'fa-text',
                'order' => 3
            ]
        ];
    }
}