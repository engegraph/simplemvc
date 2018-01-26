<?php namespace wSGI\Modules\Crm\Models;

use Core\Model;

class Recepcao extends Model
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
        return $this->hasOne('wSGI\Modules\Controles\Models\TipoMidia','Id', 'TipoMidiaId');
    }

    public function TipoMidias()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\TipoMidia','Id');
    }

    public function onBeforeSave()  {
/*var_dump($this);
die;*/
        $this->ImovelCadastrado  = ($this->ImovelCadastrado) ? 1 : 0;
        //$this->ImovelCadastrado  = ($this->ImovelCadastrado = '((0))') ? 0 : 1;

        $this->ClienteCadastrado = ($this->ClienteCadastrado) ? 1 : 0;
        //$this->ClienteCadastrado = ($this->ClienteCadastrado = '((0))') ? 0 : 1;

        $this->Encerrado         = ($this->Encerrado) ? 1 : 0;
        //$this->Encerrado         = ($this->Encerrado = '((0))') ? 0 : 1;
    }


}