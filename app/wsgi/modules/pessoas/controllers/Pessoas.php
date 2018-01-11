<?php namespace wSGI\Modules\Pessoas\Controllers;

use Core\Controller;
use Core\Session;
use wSGI\Modules\Controles\Models\Estado;

class Pessoas extends Controller
{
    public function validate()
    {
        $v = $this->validator::make([
            'nome' => 'foo',
            'email' => 'airton engegraph.com.br',
            'telefone' => ['celular'=>'25a', 'comercial'=>'']
        ],[
            'nome' => ['required','foo'],
            'email' => ['required', 'email'],
            'telefone.celular' => ['required', 'regex:/^[0-9]{0,8}$/'],
            'telefone.comercial' => 'required',
        ],[
            'nome.required' => 'Informe seu nome',
            'nome.foo' => 'Informe foo',
            'email.required' => 'Informe seu email',
            'email.email' => 'Email inválido',
            'telefone.celular.required' => 'Telefone é requirido',
            'telefone.celular.regex' => 'Somente números é permitido',
            'telefone.comercial.required' => 'Um telefone comercial é necessário',
        ]);

        var_dump($v->fails(), $v->failed());

        if($v->fails())
        {
            $Erro = $v->errors();
            var_dump($Erro->get('nome'));
        }

        echo '<hr>';

        try
        {
            $Estado = Estado::find('7A657BF0-F703-11E7-BE74-07E28952CDB9');
            $Estado->Nome = 'wsgi';
            $Estado->Uf = 'FL';
            if($Res = $Estado->save())
            {
                var_dump($Res, $Estado);
            }
            else
            {
                print_r(Session::all());
            }
        }
        catch (\Exception $exception)
        {
            echo 'Erros aconteceram :: '.$exception->getMessage();
        }

    }
}