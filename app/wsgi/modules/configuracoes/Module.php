<?php namespace wSGI\Modules\Configuracoes;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{

    public function registerNavigation(): array
    {
        return [
          'configuracoes' => [
              'url' => backend_url('/configuracoes'),
              'label' => 'Configurações',
              'icon' => 'gears',
              'permissions' => ['*'],
          ]
        ];
    }
}