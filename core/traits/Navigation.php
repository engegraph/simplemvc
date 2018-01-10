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
    public $pageIcon = null;
    private $pageHeader = null;
    public $Header;
    private $Breadcrumbs = null;

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

        if(!$this->Header)
            $this->Header = $this->Module->Menu->label;

        if(!$this->pageSubtitle)
            $this->pageSubtitle = ($this->App->Action=='index' ? $this->App->list : ucfirst($this->App->Action));

        if(!$this->pageIcon)
            $this->pageIcon = $this->Module->Menu->icon;

        //var_dump($this->App);


        $this->Breadcrumbs = <<<bread
                <ol class="breadcrumb">
                    <li>{$this->App->name}</li>
                    <li>{$this->pageModulo}</li>
                    <li>{$this->pageTitle}</li>
                    <li>{$this->pageSubtitle}</li>
                </ol>
bread;

        $this->pageHeader = <<<page
        <h1 class="page-title txt-color-blueDark">
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-{$this->pageIcon}"></i> {$this->Header} <span> > {$this->pageSubtitle} </span>
        </h1>
page;

    }


    /**
     * Exbie a numeração na grid juntamente com um link escondido para edição
     * @param int $key = Numeração na grid
     * @param string $Id ID GUID do registro
     * @return string Link para a edição do registro
     */
    final protected function checks(int $key, string $Id) : string
    {
        $uuid = $Id;
        $Id = str_guid($uuid);
        $page = $this->Request->Module.'/'.$this->Request->Controller;
        $url = backend_url("/{$page}/editar/{$uuid}");
        $name = $this->Model->getClass().'[Uuid][]';
        $str = <<< str
            <div class="checkbox checkbox-danger">
            <input type="checkbox" id="item-{$key}" name="{$name}" value="{$Id}">
            <label for="item-{$key}">{$key}</label>
</div>
            <a href="{$url}" style="display: none; visibility: hidden;" class="reg-edit"></a>
str;
        return $str;
    }


    /**
     * Cria uma toolbar com os botões de ações das páginas
     * new | save | saveclose | savenew | remove | close
     * @param string $tool
     * @param bool $group
     * @return string
     */
    final protected function actions(string $tool = 'new|remove', $group = false) : string
    {
        $bar = [];
        $tool = explode('|', $tool);
        #$url = backend_url('/'.str_replace('_','-',Inflector::tableize($this->page)));
        $url = backend_url('/'.$this->Request->Module.'/'.$this->Request->Controller);
        foreach ($tool as $val)
        {
            $val = trim($val);
            $btn = '';

            if($val == 'new')
            {
                if($url == url())
                {
                    $btn .= "<a href='{$url}/cadastro' class='btn btn-primary' title='Cadastro'>";
                    $btn .= '<i class="fa fa-plus-square"></i> Novo';
                    $btn .= '</a>';
                }
            }

            if($val == 'remove')
            {
                if($url == url())
                {
                    $btn .= '<button type="button" class="btn btn-danger disabled" disabled="disabled" id="remove-checked">';
                    $btn .= '<i class="fa fa-trash"></i> Remover';
                    $btn .= '</button>';
                }
                else
                {
                    if(property_exists($this,'_uuid'))
                    {
                        $btn .= '<button class="btn btn-link pull-left remove" type="button" name="'.$this->Module->getClass().'[Uuid][]" onclick="remove([this])" value="'.$this->_uuid.'">';
                        $btn .= '<i class="fa fa-trash"></i> <u>R</u>emover';
                        $btn .= '</button>';
                    }
                }
            }

            $name = '_save';

            if($val == 'save')
            {
                $btn .= '<button class="btn btn-success" type="submit" name="'.$name.'" value="0">';
                $btn .= '<i class="fa fa-save"></i> <u>S</u>alvar';
                $btn .= '</button>';
            }

            if($val == 'savenew')
            {
                $btn .= '<button class="btn btn-primary" type="submit" name="'.$name.'" value="1">';
                $btn .= '<i class="fa fa-save"></i> <u>S</u>alvar e novo';
                $btn .= '</button>';
            }

            if($val == 'saveclose')
            {
                $btn .= '<button class="btn btn-warning" type="submit" name="'.$name.'" value="2">';
                $btn .= '<i class="fa fa-save"></i> <u>S</u>alvar e fechar';
                $btn .= '</button>';
            }

            if($val == 'close')
            {
                $btn .= "<a href='{$url}' class='btn btn-danger'>";
                $btn .= '<i class="fa fa-close"></i> <u>F</u>echar';
                $btn .= '</a>';
            }

            $bar[$val] = $btn;
        }

        $htm = '<div class="btn-actions'.($group ? ' btn-group' : '').'">';


        foreach ($bar as $val)
            $htm .= "{$val} ";
        $htm .= '</div>';

        return $htm;
    }

}