<?php namespace wSGI\Modules\Crm\Models;

use Core\Model;

class Corretor extends Model
{
    protected $rules = [
/*      'ImovelCadastrado' => 'required',
        'ClienteCadastrado' => 'required',
        'Encerrado' => 'required'*/
    ];

    protected $ruleMessages = [
  /*      'ImovelCadastrado.required' => 'Informe se o imóvel está cadastrado',
        'ClienteCadastrado.required' => 'Informe se o cliente está cadastrado',
        'Encerrado.required' => 'Informe se a ligação foi encerrada',*/
    ];

    public $table = 'Crms';

    public function TipoMidia()
    {
        return $this->hasOne('App\Modules\wSGI\Models\TipoMidia','Id', 'TipoMidiaId');
    }

    public function TipoMidias()
    {
        return $this->hasAll('App\Modules\wSGI\Models\TipoMidia','Id');
    }


}