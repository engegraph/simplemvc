<?php

namespace wSGI\Modules\Auth\Util;

/**
 * Class Password Segurança de senha com a biblioteca HASH do autor @ircmaxell
 * @package wSGI\Modules\Auth\Util
 */

class Password
{
    /**
     * Algorítimo de criptografia utilizado
     * @var int
     */
    private static $Algo = PASSWORD_BCRYPT;

    /**
     * array de opções para customizar/fortalecer o hash que será gerado
     * @var array
     */
    private static $Options = ['cost'=>12];

    /**
     * String que terá seu hash gerado
     * @var string
     */
    private static $Pass;

    /**
     * Hash gerado apartir da senha informada
     * @var string
     */
    private static $Hash;

    /**
     * Setando um hash para uma senha
     * @param $Pass
     * @param null $Algo
     * @param array $Opt
     * @return bool|string
     */
    public static function hash($Pass, $Algo = null, array $Opt = [])
    {
        self::$Pass = $Pass;
        self::$Algo = $Algo ? $Algo : self::$Algo;
        self::$Options = $Opt ? $Opt : self::$Options;
        self::$Hash = password_hash(self::$Pass,self::$Algo,self::$Options);
        return self::$Hash;
    }


    /**
     * Verificando se uma senha confere com seu hash
     * @param $Pass
     * @param null $Hash
     * @return null
     */
    public static function verify($Pass, $Hash = null)
    {
        $Hash = $Hash ? $Hash : self::$Hash;
        if(password_verify($Pass,$Hash))
        {
            if(password_needs_rehash($Hash,self::$Algo,self::$Options))
                echo 'need rehash';

            return TRUE;
        }
    }
}