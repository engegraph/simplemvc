<?php namespace wSGI\Modules\Auth\Models;

use Core\Model;

class User extends Model
{
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
        'Login.username' => 'Nome de usu[ario deve conter apenas letras, números, pontos, traços e underlines',
        'Senha.required' => 'Nome de usu[ario deve conter apenas letras, números, pontos, traços e underlines',
        'Senha.min' => 'Uma senha com no mínimo 6 caracteres',
        'Senha.confirmed' => 'As senhas não conferem',
    ];
    /**
     * @var string $table Tabela no banco de dados
     */
    public $table = 'Usuarios';

    /**
     * @var string $connection Nome da conexão a ser utilizada
     */
    public $connection = 'auth';
}