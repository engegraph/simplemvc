<?php namespace wSGI\Common\Util;

/**
 * Class Validator = Recurso de validação. Essa classe deverá ser extendida pelas classes de validações específicas de cada do módulo.
 * Todo método a ser implementado deve ser prefixado com 'validate', exemplo: validateMinhaFuncao
 * Nos modeos ou onde for usar o serviço de validação, basta informar nas rules o nome da função sem prefixo, exemplo: ['nome'=>'minhafuncao']
 * @link https://laravel.com/docs/5.5/validation
 * @package wSGI\Common\Util
 */

class Validator extends \Illuminate\Validation\Validator
{
    public function validateUsername($attribute, $value, $parameters)
    {
        return preg_match('/^[a-z0-9\.\-\_]+$/i', $value);
    }
}