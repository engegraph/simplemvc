<?php namespace wSGI\Modules\Nejs;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'nejs' => [
                'url' => 'javascript:void(0);',
                'label' => 'Notif, ExtraJud.',
                'icon' => 'envelope',
                'permissions' => ['*'],
                'childs' => [
                    [
                        'url' => backend_url('/nejs/ctrl-notificaos'),
                        'label' => 'Controle de notificações extrajudiciais',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/nejs/ctrl-prazos'),
                        'label' => 'Controle de prazos',
                        'permissions' => ['*'],
                    ]
                ],
            ]
        ];
    }
}