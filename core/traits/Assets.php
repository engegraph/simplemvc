<?php namespace Core\Traits;

trait Assets
{
    private $assets = [];

    final protected function addStyle($Style)
    {
        if(is_string($Style))
            $this->assets['css'][] = $this->add($Style);
        elseif(is_array($Style))
            foreach ($Style as $item)
                $this->assets['css'][] = $this->add($item);

       // $this->assets['css'][] = $this->add($Style);
    }

    final protected function addScript($Script)
    {
        if(is_string($Script))
            $this->assets['js'][] = $this->add($Script);
        elseif(is_array($Script))
            foreach ($Script as $item)
                $this->assets['js'][] = $this->add($item);

        //$this->assets['js'][] = $this->add($Script);
    }

    private function add(string $path) : string
    {
        $file = $path;
        if(substr($path,0,7)=='assets/')
        {
            $path = substr($path,7);
            $file = $this->pathAssets($path);
        }
        return $file;
    }

    final protected function scripts()
    {
        $scripts = $this->getStylesAndScripts('js');
        return $scripts;
    }

    final protected function styles()
    {
        $styles = $this->getStylesAndScripts('css');
        return $styles;
    }

    private function getStylesAndScripts(string $type) : string
    {
        $Htm = '';
        if(sizeof($this->assets))
        {
            if(isset($this->assets[$type]) && !empty($this->assets[$type]))
            {
                $files = $this->assets[$type];
                $Htm    .= '<!-- Adicionando '.($type == 'js' ? 'Scripts' : 'Estilos').' -->'.PHP_EOL;
                $format = $type=='css' ? '<link rel="stylesheet" type="text/css" media="screen" href="%s">' : '<script src="%s"></script>';
                foreach ($files as $file)
                    $Htm .= sprintf($format, $file).PHP_EOL;
            }
        }

        $rawAssets = ['css'=>'style', 'js'=>'script'];

        if(isset($this->assets[$rawAssets[$type]]) && !empty($this->assets[$rawAssets[$type]]))
            foreach ($this->assets[$rawAssets[$type]] as $asset)
                $Htm .= $asset.PHP_EOL;

        return $Htm;
    }
}