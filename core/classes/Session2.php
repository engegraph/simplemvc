<?php namespace Core\Classes;

/**
 * Class Session2
 * @package Core\Classes
 */

class Session2
{
    /**
     * @var string $prefix prefixo para nomes de sessões
     */
    private static $prefix = '__wsgi';


    /**
     * * Seta uma nova sessão
     *
     * @param $name string nome da unção
     * @param $val mixed Valor a ser guardado
     */
    public static function set($name, $val)
    {
        $Name = wrap(self::$prefix.'.'.$name, true);
        eval('$_SESSION'.$Name.' = $val;');
    }


    /**
     * * Recupera o valor de uma sessão
     *
     * @param string $name nome da sessão a ser recuperada
     * @return mixed Valor retornado
     */
    public static function get(string $name) : mixed
    {
        $assi = wrap(self::$prefix.'.'.$name, true);
        return eval('return !isset($_SESSION'.$assi.') ? NULL : $_SESSION'.$assi.'; ');
    }


    /**
     * * Verifica se uma sessão existe
     *
     * @param string $name sessão a ser verificada
     * @return bool retorna verdadeiro ou falso
     */
    public static function has(string $name) : bool
    {
        $assi = wrap(self::$prefix.'.'.$name, true);
        return eval('return isset($_SESSION'.$assi.') ? TRUE : FALSE;');
    }


    /**
     * * Elimina uma variavel da sessão
     *
     * @param string|null $name
     * @return void
     */
    public static function del(string $name = null) : void
    {
        $assi = self::$prefix;
        if($name) $assi .= '.'.$name;
        $assi = wrap($assi, true);
        eval('if(isset($_SESSION'.$assi.')) unset($_SESSION'.$assi.'); return ;');
    }


    /**
     * * Destroi completamente a sessão
     *
     * @return void
     */
    public static function destroy() : void
    {
        # Apagando as variáveis da sessão
        $_SESSION = [];

        # Removendo cookies da sessão
        if(ini_get('session.use_cookies'))
        {
            $params = session_get_cookie_params();
            $params['expire'] = time() - 42000;
            Cookie::set(session_name(), '', $params);
        }

        # Destruindo a sessão
        if(session_status()==PHP_SESSION_ACTIVE)
            session_destroy();
    }


    /**
     * * Retorna todas as variáveis na sessão
     *
     * @return array
     */
    public static function all() : array
    {
        return $_SESSION;
    }
}