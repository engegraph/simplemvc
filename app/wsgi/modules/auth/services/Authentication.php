<?php namespace wSGI\Modules\Auth\Services;

use Core\Classes\Message;
use wSGI\Modules\Auth\Models\User;
use wSGI\Modules\Auth\Util\Password;

class Authentication
{
    /**
     * * Autentica o usuário
     *
     * @param $user string email/username
     * @param $pass string senha
     * @return array
     */
    public function login($user, $pass)
    {
        $user = User::where('email', $user)->orWhere('login', $user)->first();

        if(!$user)
            return ['alert'=>['type'=>'warning','content'=>'O nome de usuário não existe','title'=>'Dados inválidos']];

        if(!Password::verify($pass, $user->Senha))
            return ['alert'=>['type'=>'warning', 'content'=>'Senha não confere. Você possui 3 tentativas.','title'=>'Dados inválidos']];

        Message::success("Olá <b>{$user->Nome}</b>. Seja bem vindo(a) !");

        return ['redirect' => backend_url('/')];
    }
}