<?php namespace Core\Traits;

trait Files
{
    private $assets = [];

    final protected function addStyle(string $pathCss)
    {
        $this->assets['css'][] = $this->add($pathCss);
    }

    final protected function addScript(string $pathJs)
    {
        $this->assets['js'][] = $this->add($pathJs);
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
        $js = $this->_get('js');
        return $js;
    }

    final protected function styles()
    {
        $css = $this->_get('css');
        return $css;
    }

    private function _get(string $type) : string
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