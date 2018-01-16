<?php namespace wSGI\Common\Validation;

/**
 * Class Validator = Recurso usado no laravel, essa classe deverá ser extendida pelas classes de validações específicas de cada do módulo.
 * Todo método a ser implementado deve ser prefixado com 'validate', exemplo: validateMinhaFuncao
 * Nos modeos ou onde for usar o serviço de validação, basta informar nas rules o nome da função sem prefixo, exemplo: ['nome'=>'minhafuncao']
 * @link https://laravel.com/docs/5.5/validation
 * @package wSGI\Common\Validation
 */

class Validator extends \Illuminate\Validation\Validator
{
    public function validateUsername($attribute, $value, $parameters)
    {
        return preg_match('/^[a-z0-9\.\-\_]+$/i', $value);
    }
}