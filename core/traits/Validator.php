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
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    private function validate(array $data = []) : bool
    {
        /**
         * Realizando validação
         */
        $data = !empty($data) ? $data : $this->findData(post(), ($name=$this->formname) ? $name : $this->getClass());
        $data = array_filter($data, 'is_scalar');
        if(!empty($this->rules))
        {

            /**
             * Disparando evento onBeforeValidate
             */
            if(method_exists($this, 'onBeforeValidate')) $this->onBeforeValidate($data);


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
            if(method_exists($this, 'onAfterValidate')) $this->onAfterValidate($data);
        }

        return true;
    }
}