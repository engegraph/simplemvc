<?php namespace wSGI\Modules\Index\Controllers;

use Core\Controller;

class Index extends Controller
{
    public $model = null;
    public $Header = 'wSGI Controle de Imobiliárias';
    public $pageSubtitle = 'Esse é seu painel';

    public function onRun(): void
    {
        # Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip
        $this->addScript(tpl_assets('js/plugin/flot/jquery.flot.cust.min.js'));
        $this->addScript(tpl_assets('js/plugin/flot/jquery.flot.resize.min.js'));
        $this->addScript(tpl_assets('js/plugin/flot/jquery.flot.time.min.js'));
        $this->addScript(tpl_assets('js/plugin/flot/jquery.flot.tooltip.min.js'));

        # Vector Maps Plugin: Vectormap engine, Vectormap language
        $this->addScript(tpl_assets('js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js'));
        $this->addScript(tpl_assets('js/plugin/vectormap/jquery-jvectormap-world-mill-en.js'));

        # Full Calendar
        $this->addScript(tpl_assets('js/plugin/fullcalendar/jquery.fullcalendar.min.js'));

        # PAGE RELATED SCRIPTS
        $this->addScript('assets/js/index.js');
    }
}