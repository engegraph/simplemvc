<?php namespace wSGI\Modules\Alugueis\Controllers;

use Core\Model;

class Chafe extends Model
{
    protected $table = 'ImovelControleChaves';

    public function TipoDocumento() {

        return $this->hasOne('wSGI\Modules\Controles\Models\TipoDocumento','Id', 'TipoDocumentoId');
    }

    public function TipoDocumentos() {

        return $this->hasAll('wSGI\Modules\Controles\Models\TipoDocumento');
    }

    public function Imovel() {

        return $this->hasOne('wSGI\Modules\Imoveis\Models\Imovel','Id', 'ImovelId');
    }

    //Eventos
    public function onBeforeSave()  {
        //Chaves estrangerias
        $this->TipoDocumentoId   = ($val = $this->TipoDocumentoId) ? $val : null;

        //Campos bit
        $this->Devolvido            = ($val = $this->Devolvido) ? $val : 0;

        //Data 1900-01-01 00:00:00
        $this->DataRetirada         = ($this->DataRetirada == '1900-01-01 00:00:00' ? null : $this->DataRetirada);
        $this->DataRetirada         = ($this->DataRetirada == '' ? null : $this->DataRetirada);
        $this->DataRetirada         = ($this->DataRetirada == 'dd/mm/aaaa' ? null : $this->DataRetirada);
        $this->DataEntrega          = ($this->DataEntrega == '1900-01-01 00:00:00' ? null : $this->DataEntrega);
        $this->DataEntrega          = ($this->DataEntrega == '' ? null : $this->DataEntrega);
        $this->DataEntrega          = ($this->DataEntrega == 'dd/mm/aaaa' ? null : $this->DataEntrega);
    }
}