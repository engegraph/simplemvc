<?php namespace wSGI\Modules\Empreendimentos\Controllers;

use Core\Controller;

class Empreendimentos extends Controller
{
    public function editar($id)
    {

        //Carrega o grafico das ligações por mes
        $Sql    = " select e.Quadra as Quadra, count(e.Quadra) as LoteNaQuadra, (select count(ee.Quadra) as Vendidos " .
				  "											                     from imoveis i join enderecos ee on i.EnderecoId = ee.Id " .
				  "											                     where i.Vendido = 1 " .
                  "                                                              and ee.Quadra = e.quadra) as Vendidos " .
                  " from imoveis i join enderecos e on i.EnderecoId = e.Id " .
                  " where i.EmpreendimentoId = '" . $id . "' " .
                  " group by e.Quadra  ";


        $this->EstatisticasPorQuadra = $this->model->rawQuery($Sql);

        //Carrega os anexos
        if (file_exists(path_storage() . '/empreendimentos/' . $id))    {

            $types = array( 'png', 'jpg', 'jpeg', 'gif' );
            if ( $handle = opendir(path_storage() . '/empreendimentos/' . $id) ) {
                while ( $entry = readdir( $handle ) ) {
                    $ext = strtolower( pathinfo( $entry, PATHINFO_EXTENSION) );
                    if( in_array( $ext, $types ) )
                        $AnexosListados[] = '../../../storage/empreendimentos/' . $id . '/' .$entry;
                }
                closedir($handle);
            }

            $this->Anexos = $AnexosListados;
        } else {
            $this->Anexos = [];
        }

        return parent::editar($id); // TODO: Change the autogenerated stub
    }

    public function onGraficoLotes()    {

        $Sql = " select count(i.Id) as LoteNaQuadra, (select count(i.Id) " .
               "									  from imoveis i " .
               "									  where i.Vendido = 1 " .
               "                                      and   i.EmpreendimentoId = '" . Post('EdEmpreendimentoId') . "') as Vendidos " .
               " from imoveis i " .
               " where i.EmpreendimentoId = '" . Post('EdEmpreendimentoId') . "' ";



        $GraficoLotes = $this->model->rawQuery($Sql);

        if ($GraficoLotes != '') {
            $GraficoLotesLabel[] = 'Não vendidos';
            $GraficoLotesLabel[] = 'Vendidos';

            $GraficoLotesData[]  = $GraficoLotes->LoteNaQuadra - $GraficoLotes->Vendidos ;
            $GraficoLotesData[]  = $GraficoLotes->Vendidos ;

        } else {
            $GraficoLotesLabel[] = 'erro';
            $GraficoLotesData[]  = 'erro';
        }


        return [
            'LabelLotes'  => $GraficoLotesLabel,
            'DataLotes'   => $GraficoLotesData,
        ];
    }

    public function PostImg()   {

        $PathStorage = path_storage() . 'empreendimentos/';
        $PathEmpreendimentoAtual = $PathStorage . Post('EmpreendimentoId') . '/';

        //Verifica se a pasta existe
        if (file_exists($PathStorage) == false)    {
            mkdir($PathStorage);
        }

        //Verifica se a pasta especifica existe
        if (file_exists($PathEmpreendimentoAtual) == false)    {
            mkdir($PathEmpreendimentoAtual);
        }

        $TempExtensao = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $TempFileName = md5_file($_FILES['file']['tmp_name']) . '.' . $TempExtensao;


//        move_uploaded_file($_FILES['file']['tmp_name'],$PathEmpreendimentoAtual . $_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'],$PathEmpreendimentoAtual . $TempFileName);
        echo json_encode(["teste"=>$_FILES]);
    }


    public function onRun(): void    {

        //Adiciona o lightGallery
        $this->addStyle(common_assets('plugin/lightGallery-master/dist/css/lightgallery.css'));
        $this->addScript(common_assets('plugin/lightGallery-master/dist/js/lightgallery.min.js'));
        $this->addScript('https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js');
        $this->addScript(common_assets('plugin/lightGallery-master/modules/lg-thumbnail.min.js'));
        $this->addScript(common_assets('plugin/lightGallery-master/modules/lg-fullscreen.min.js'));

        //Adiciona o dropzone
        $this->addScript(tpl_assets('js/plugin/dropzone/dropzone.min.js'));

        //Adiciona o ChartsJS
        $this->addScript(tpl_assets('js/plugin/chartjs/chart.min.js'));



        $this->addScript('assets/js/empreendimentos.js');
    }

}