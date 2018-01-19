<?php namespace wSGI\Modules\Administracao\Models;

use Carbon\Carbon;
use Core\Model;
use wSGI\Modules\Auth\Util\Password;

class Usuario extends Model
{
    protected $table = 'Usuarios';

    public $rules = [
        'Nome' => 'required',
        'Email' => 'required|email',
        'Login' => 'required|username',
        'Senha' => 'required|min:6',
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

    protected $dateFormat = '';

    public $connection = 'auth';


    /**
     * Validação manul de alguns campos
     */
    public function onBeforeSave()
    {
        # Removendo campo de confirmação de senha
        unset($this->Senha_confirmation);

        # Criptografando a senha
        $this->Senha = ($pass=post('Usuario.Senha')) ? Password::hash($pass) : $this->Senha;

        /*var_dump($this->toArray(), $this->attributes);
        die;*/
    }
}
