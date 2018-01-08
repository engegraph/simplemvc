<?php namespace Core\MiddleWares;

class Nav
{
    public static function getNavigation(array $collection) : string
    {
        return self::render($collection);
    }


    private static function render(array $menu, $htm = '', $level=0) : string
    {
        if(sizeof($menu))
        {
            if($level===0)
                $htm .= '<u>';

            foreach ($menu as $nav)
            {
                $htm .= '<li';
                $htm .= '>';
                $htm .= '<a href="#"';
                $htm .= '>'.$nav['label'].' - '.$level.'</a>';

                $submenu = isset($nav['childs']) ? $nav['childs'] : null;

                if($submenu)
                {
                    $level++;
                    $htm .= '<ul>';
                    $htm = self::render($submenu, $htm, $level);
                    $htm .= '</ul>';
                    $level--;
                }
                $htm .= '</li>';
            }
            if($level===0)
                $htm .= '</ul>';
        }
        return $htm;
    }

    private static function order(array &$menu)
    {
        $data = [];
        foreach ($menu as $nav)
        {
            $order = isset($nav['order']) ? $nav['order'] : (sizeof($menu)+1);
            if($order >= 0)
                $data[$order] = $nav;
        }
        $menu = $data;
    }
}