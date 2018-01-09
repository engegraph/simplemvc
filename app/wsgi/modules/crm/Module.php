<?php  namespace wSGI\Modules\Crm;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'crm' => [
                'url' => 'javascript:void(0);',
                'label' => 'CRM',
                'icon' => 'phone-square',
                'permissions' => ['*'],
                'order' => 2,
                'childs' => [
                    [
                        'url' => backend_url('/crm/recepcao'),
                        'label' => 'Recepção',
                        'permissions' => ['*'],
                    ],
                    [
                        'url' => backend_url('/crm/corretor'),
                        'label' => 'Corretor',
                        'permissions' => ['*'],
                    ]
                ],
            ]
        ];
    }
}