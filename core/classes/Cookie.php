<?php namespace Core\Classes;

/**
 * Class Cookie Responsável por gerenciamento de cookies do browser
 * @package Core\Classes
 */

class Cookie
{
    /**
     * @var string $prefix Prefixo usado em todos os cookies. Util para identificar os cookies específicos do sistema
     */
    private static $prefix = '__wsgi';


    /**
     * ** Define um cookie
     *
     * bool setcookie ( string $name [, string $value = "" [, int $expire = 0 [, string $path = "" [, string $domain = "" [, bool $secure = false [, bool $httponly = false ]]]]]] )
     * @link http://php.net/manual/pt_BR/function.setcookie.php
     * @param string $name
     * @param string $value
     * @param array $opt
     * @return mixed
     */
    public static function set(string $name, string $value, array $opt = [])
    {
        $params = ['expire'=>0, 'path'=>self::info('path'), 'domain'=>self::info('host'), 'secure'=>true, 'httponly'=>true];
        $assin = '"'.self::wrap($name).'", "'.$value.'"';
        foreach ($params as $k => $v)
        {
            $value = (isset($opt[$k]) ? $opt[$k] : $v);
            $assin .= ', '.(is_string($value) ? '"'.$value.'"' : $value);
        }
        return eval('return setcookie('.$assin.');');
    }

    /**
     * * Verifica se um cokkie existe
     *
     * @param string $name
     * @return bool
     */
    public static function has(string $name) : bool
    {
        $Name = self::wrap($name, true);
        return eval('return isset($_COOKIE'.$Name.') ? TRUE : FALSE;');
    }

    /**
     * * Recupera um cookie
     *
     * @param string $name
     * @return string
     */
    public static function get(string $name) : string
    {
        $Name = self::wrap($name, true);
        return eval('return isset($_COOKIE'.$Name.') ? $_COOKIE'.$Name.' : NULL;');
    }


    /**
     * * Remove um cookie
     *
     * @param string|null $name
     * @return bool
     */
    public static function del(string $name = null) : bool
    {
        $trash = self::wrap($name, true);
        eval('unset($_COOKIE'.$trash.');');
        return setcookie(self::wrap($name), "", time()-3600);
    }


    /**
     * * Resolve a nomeclatura de cookie com pontos para a nomeclatura de array
     *
     * @param $name string Nome amigável do cookie
     * @return string nome readl do cooke
     */
    private static function wrap($name = null, $aspas=false) : string
    {
        $name  = $name ? self::$prefix.'.'.$name : self::$prefix;
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
     * informações do domínio
     * @return string
     */
    private static function info($info) : string
    {
        $parse = parse_url(url());
        return $parse[$info];
    }
}