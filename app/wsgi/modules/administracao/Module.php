<?php namespace wSGI\Modules\Administracao;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{

    public function registerNavigation(): array
    {
        return [
            'administracao' => [
                'label'  => 'Administração',
                'url'    => 'javascript:void(0)',
                'icon'   => 'key',
                'childs' => [
                    [
                        'label' => 'Usuários',
                        'url'   => backend_url('/administracao/usuarios'),
                        'icon'  => 'user-circle',
                    ]
                ]
            ]
        ];
    }
}