<?php namespace Core\Traits;

use Core\Session;

/**
 * Validação dos modelos
 * Trait Validator
 * @package Core\Traits
 */

trait Validator
{
    protected $rules = [];

    protected $ruleMessages = [];

    /**
     * @return bool
     */
    private function validate() : bool
    {
        /**
         * Disparando evento onBeforeValidate
         */
        $this->onBeforeValidate();

        /**
         * Realizando validação
         */
        $data = $this->toArray();
        if(!empty($this->rules))
        {
            $v = \Core\Services\Validator::make($data, $this->rules, $this->ruleMessages);
            if($v->fails())
            {
                $model = $this->getClass();
                $erro = $v->errors();
                foreach ($v->failed() as $field => $format)
                {
                    $message = $erro->get($field);
                    Session::set("err.{$model}.{$field}", $message);
                    return false;
                }
            }
        }

        /**
         * Disparando evento onAfterValidate
         */
        $this->onAfterValidate();

        return true;
    }

    /**
     * Evento realizado antes de iniciar a validação
     */
    protected function onBeforeValidate(){}

    /**
     * Evento realizado após a validação
     */
    protected function onAfterValidate(){}
}