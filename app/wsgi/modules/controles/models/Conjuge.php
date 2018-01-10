<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class Conjuge extends Model
{

    protected $table = 'Pessoas';

    public function profissoes()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Profissao');
    }

    public function parents()
    {
        return self::all();
    }

    public function nacionalidades()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Nacionalidade');
    }

    public function naturalidades()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Naturalidade');
    }

    public function estadoscivil()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\EstadoCivil');
    }

    public function enderecos()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Endereco');
    }

    public function Endereco()
    {
        return $this->hasOne('wSGI\Modules\Controles\Models\Endereco','Id', 'EnderecoId');
    }

    public function Cidades()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Cidade');
    }
}