<?php namespace Core\Classes;

/**
 * Class Cookie Responsável por gerenciamento de cookies do browser
 * @package Core\Classes
 */

class Cookie
{
    /**
     * @var string $prefix Trabalhar todos os cookies com este prefixo. Definir null para desabilitar.
     */
    public static $prefix = '__wsgi';


    /**
     * bool setcookie ( string $name [, string $value = "" [, int $expire = 0 [, string $path = "" [, string $domain = "" [, bool $secure = false [, bool $httponly = false ]]]]]] )
     * @link http://php.net/manual/pt_BR/function.setcookie.php
     * @param string $name
     * @param string $value
     * @param array $opt
     * @return mixed
     */
    public static function set(string $name, string $value, array $opt = [])
    {
        $params = ['expire'=>0, 'path'=>self::get('path'), 'domain'=>self::get('host'), 'secure'=>true, 'httponly'=>true];
        $assin = '"'.self::wrap($name).'", "'.$value.'"';
        foreach ($params as $k => $v)
        {
            $value = (isset($opt[$k]) ? $opt[$k] : $v);
            $assin .= ', '.(is_string($value) ? '"'.$value.'"' : $value);
        }
        return eval('return setcookie('.$assin.');');
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function has(string $name) : bool
    {
        return self::wrap('has', $name);
    }

    /**
     * @param string $name
     * @return string
     */
    public static function pull(string $name) : string
    {
        return self::wrap('get', $name);
    }


    /**
     * * Remove um cookie
     *
     * @param string|null $name
     * @return bool
     */
    public static function trash(string $name = null) : bool
    {
        $prefix = self::$prefix;
        self::$prefix = null;
        $name = $name ? $name : $prefix;
        $trash  = self::wrap($name, true);
        eval('unset($_COOKIE'.$trash.');');
        return setcookie(self::wrap($name), "", time()-3600);
    }


    /**
     * * Converte a nomeclatura de nome de pontos para arrays, para suprir a nomeclatura padrão de cookie
     *
     * @param $name string Nome amigável do cookie
     * @return string nome readl do cooke
     */
    private static function wrap($name, $aspas=false) : string
    {
        $name  = ($prefix=self::$prefix) ? $prefix.'.'.$name : $name;
        $count = strlen($name);
        $fpos  = strpos($name, '.'); // Primeira ocorrencia de ponto
        $lpos  = strrpos($name, '.'); // Ultima ocorrencia de ponto

        if($fpos !== FALSE)
        {
            $str   = $aspas ? '["' : '';
            for ($i=0; $i < $count; $i++)
            {
                $char = $name{$i};

                if($char=='.')
                {
                    if($i==$fpos)
                        $char = $aspas ? '"]["' : '[';

                    if( $i > $fpos && $i <= $lpos )
                        $char =  $aspas ? '"]["' : '][';
                }
                $str .= $char;
            }
            $str .= $aspas ? '"]' : ']';
            $name = $str;
        }
        else
            $name = $aspas ? '["'.$name.'"]' : $name;

        return $name;
    }


    /**
     * @param string $name
     * @param string|null $var
     * @param string|null $val
     * @return bool
     */
    private static function resolve(string $name, string $var = null, string $val = null)
    {
        switch ($name)
        {
            case 'set':
                echo self::wrap($var);
                break;

            case 'has':
                // Verifica cookie
                break;

            case 'get':
                // Recupera cookie
                break;

            case 'trash':
                // Remover cookie
                break;

        }

        return true;
    }

    /**
     * informações do domínio
     * @return string
     */
    private static function get($info) : string
    {
        $parse = parse_url(url());
        return $parse[$info];
    }
}