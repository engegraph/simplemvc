<?php namespace Core\Traits;

use Core\Classes\Session;

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
         * Realizando validação
         */
        $data = $this->toArray();
        if(!empty($this->rules))
        {

            /**
             * Disparando evento onBeforeValidate
             */
            $this->onBeforeValidate();


            $v = \Core\Services\Validation\Validator::make($data, $this->rules, $this->ruleMessages);
            if($v->fails())
            {
                $model = $this->getClass();
                $erro = $v->errors();
                foreach ($v->failed() as $field => $format)
                {
                    $message = $erro->first($field);
                    Session::set("err.{$model}.{$field}", $message);
                    throw new \Exception($message);
                }
            }

            /**
             * Disparando evento onAfterValidate
             */
            $this->onAfterValidate();
        }

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