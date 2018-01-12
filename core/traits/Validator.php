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
     * Preenche o objeto setando suas propriedades
     * com os valores do formulário submetido
     * @return array
     */
    public function populate(array $data)
    {
        if(!empty($data))
            foreach ($data as $prop => $val)
                $this->setProperty($prop, $val);
    }


    /**
     * Seta um valor para propriedade e o guarda em uma sessão
     * @param $name
     * @param $value
     */
    private function setProperty($name, $value) : void
    {
        if(in_array($name, array_keys($this->attributes)))
        {
            $model = $this->getClass();
            $val = $this->$name = ($v=$value) ? $v : NULL;
            Session::set("val.{$model}.{$name}", $val);
        }
    }


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
                    $message = $erro->first($field);
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