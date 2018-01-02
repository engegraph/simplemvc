<?php namespace App\wSGI\Common\Aluguel;

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
                [
                    'label'       => 'Imóveis',
                    'url'         => 'aluguel/central',
                    'icon'        => 'icon-pencil',
                    'permissions' => ['*'],
                ],
                [
                    'label'       => 'Imóveis',
                    'url'         => 'aluguel/controle-de-chaves',
                    'icon'        => 'icon-pencil',
                    'permissions' => ['*'],
                ]
            ]
        ];
    }


    public function registerAccess()
    {
        return [
            'access' => 'free'
        ];
    }
}