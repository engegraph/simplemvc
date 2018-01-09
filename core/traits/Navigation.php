<?php namespace Core\Traits;

use Core\MiddleWares\Nav;
use Core\Providers\ModuleBase;
use Doctrine\Common\Inflector\Inflector;

trait Navigation
{
    use Module;

    public $pageModulo = null;
    public $pageTitle = null;
    public $pageSubtitle = null;
    public $Header;
    private $Breadcrumbs = null;
    private $pageHeader = null;

    final private function nav()
    {
        $Htm = $this->render($this->Menu);
        /*$handler = fopen('nav.html','w+');
        fwrite($handler, $Htm);
        fclose($handler);*/
        return $Htm;
    }

    final private function onNavigationInit()
    {
        // Configurar menu
    }

    private function render(array $menu, $htm = '', $level=0) : string
    {
        if(sizeof($menu))
        {
            if($level==0)
                $htm .= '<ul class="wSGI-navigation">';

            foreach ($menu as $nav)
            {
                $htm .= '<li';
                if(active_item($nav['url']))
                    $htm .= ' class="active"';
                $htm .= '>';

                $label = '';
                $title = $nav['label'];
                if(isset($nav['icon']))
                    $label .= '<i class="fa fa-fw fa-'.$nav['icon'].($level===0 ? ' fa-lg': '').'"></i>';
                $label .= " <span class='menu-item-parent'>{$title}</span>";

                $attr = ['title'=>$title];
                if(isset($nav['attributes']))
                    $attr = array_merge($attr, $nav['attributes']);

                $htm .= '<a href="'.$nav['url'].'"';
                foreach ($attr as $name => $val)
                    $htm .= " {$name}=\"{$val}\"";
                $htm .= '>'.$label.'</a>';

                $submenu = isset($nav['childs']) ? $nav['childs'] : null;

                if($submenu)
                {
                    $level++;
                    $htm .= '<ul>';
                    $htm  = $this->render($submenu, $htm, $level);
                    $htm .= '</ul>';
                    $level--;
                }
                $htm .= '</li>';
            }
            if($level==0)
                $htm .= '</ul>';
        }
        return $htm;
    }


    final private function setBreadCrumbs()
    {
        if(!$this->pageModulo)
            $this->pageModulo = ucfirst($this->Request->Module);

        if(!$this->pageTitle)
            $this->pageTitle = ucfirst($this->Request->Controller);

        if(!$this->pageSubtitle)
            $this->pageSubtitle = $this->App['list'];

        if(!$this->Header)
            $this->Header = $this->Module->Menu->label;

        //var_dump($this->App);


        $this->Breadcrumbs = <<<bread
                <ol class="breadcrumb">
                    <li>{$this->App['name']}</li>
                    <li>{$this->pageModulo}</li>
                    <li>{$this->pageTitle}</li>
                    <li>{$this->pageSubtitle}</li>
                </ol>
bread;

        $this->pageHeader = <<<page
        <h1 class="page-title txt-color-blueDark">
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-{$this->Module->Menu->icon}"></i> {$this->Header} <span> > {$this->pageSubtitle} </span>
        </h1>
page;

    }

}