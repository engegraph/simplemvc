<?php namespace wSGI\Modules\Nejs\Models;

use Core\Model;

class Notificaco extends Model
{
    public $table = 'Nejs';

    public function Empreendimento()
    {
        return $this->hasOne('App\Modules\wSGI\Models\Empreendimento','Id', 'EmpreendimentoId');
    }

    public function Imovel()
    {
        return $this->hasOne('App\Modules\wSGI\Models\Imovel','Id', 'ImovelId');
    }
}