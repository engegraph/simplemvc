<?php namespace wSGI\Modules\Pessoas\Util;

class Validator extends \wSGI\Common\Util\Validator
{
    public function validateWsgi($attribute, $value, $parameters)
    {
        return $value == 'wsgi';
    }
}