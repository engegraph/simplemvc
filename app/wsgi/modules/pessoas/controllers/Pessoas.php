<?php namespace wSGI\Modules\Pessoas\Controllers;

use Core\Controller;
use Core\Session;
use wSGI\Modules\Controles\Models\Estado;
use wSGI\Modules\Pessoas\Models\Pessoa;

class Pessoas extends Controller
{
    public function editar($Uuid)
    {
        $Pessoa = Pessoa::find($Uuid);
        $relation = 'Endereco->Cidade->Nome';
        var_dump($Pessoa->{$relation});

        /*$v = $this->validator::make([
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

        echo '<hr>'; */

        #$this->val('Pessoa.Endereco.Cidade.Nome');

        /*try
        {
            $Estado = Estado::find('00000003-0000-0000-0000-000000000000');
            $Estado->Nome = 'Flórida';
            $Estado->Uf = 'FL';
            if($Res = $Estado->save())
            {
                var_dump($Res, $Estado);
            }
            else
            {
                echo '<pre>';
                print_r(Session::all());
                $this->val('Pessoa.Endereco.Cidade.Nome');
                echo '</pre>';
            }
        }
        catch (\Exception $exception)
        {
            echo 'Erros aconteceram :: '.$exception->getMessage();
        } */

    }
}