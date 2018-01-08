<?php namespace wSGI\Modules\Index;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'index' => [
                'url' => backend_url('/'),
                'label' => 'Dashared',
                'icon' => 'fa-text',
                'order' => 1
            ]
        ];
    }
}