<?php namespace wSGI\Modules\Administracao\Models;

use Core\Model;

class Usuario extends Model
{
    protected $table = 'Usuarios';

    public $rules = [
        'Nome' => 'required',
        'Email' => 'required|email',
        'Login' => 'required|username',
        'Senha' => 'required|min:6|confirmed',
    ];

    public $ruleMessages = [
        'Nome.required' => 'Informe seu nome',
        'Email.required' => 'Informe seu email',
        'Email.email' => 'Email inválido',
        'Login.required' => 'Informe um nome de usuário',
        'Login.username' => 'Nome de usuário deve conter apenas letras, números, pontos, traços e underlines',
        'Senha.required' => 'Informe uma senha',
        'Senha.min' => 'A senha deve conter no mínimo 6 caracteres',
        'Senha.confirmed' => 'As senhas não conferem',
    ];

    protected $dateFormat = null;

    public $connection = 'auth';

    /**
     * Trabalhando a senha
     */
    public function onBeforeValidate()
    {
        if($this->Id)
        {
            $this->rules['Senha'] = 'min:6|confirmed';
        }
    }


    /**
     * Validação manul de alguns campos
     */
    public function onBeforePopulate(&$attributes)
    {
        # Removendo campo de confirmação de senha
        unset($attributes['Senha_confirmation']);

        # Criptografando a senha
        $attributes['Senha'] = ($pass = trim($attributes['Senha'])) ? $this->hash($pass) : $this->Senha;
    }
}
