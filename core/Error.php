<?php namespace Core;

/**
 * Class Error Assume a responsabilidade sobre os erros ocorridos pelo PHP
 * @package Core
 * @author Airton Lopes <airton@engegraph.com.br>
 */

class Error
{
    /**
     * Códigos e suas respctivas constantes de erros
     */
    const ERRORS = [
        1     => 'E_ERROR',
        2     => 'E_WARNING',
        4     => 'E_PARSE',
        8     => 'E_NOTICE',
        16    => 'E_CORE_ERROR',
        32    => 'E_CORE_WARNING',
        64    => 'E_COMPILE_ERROR',
        128   => 'E_COMPILE_WARNING',
        256   => 'E_USER_ERROR',
        512   => 'E_USER_WARNING',
        1024  => 'E_USER_NOTICE',
        2048  => 'E_STRICT',
        4096  => 'E_RECOVERABLE_ERROR',
        8192  => 'E_DEPRECATED',
        16384 => 'E_USER_DEPRECATED',
    ];

    private function __construct(){}

    /**
     * @param $errno = Número do código de erro
     * @param $errstr = Mensagem descritiva do erro
     * @param $errfile = Arquivo do erro
     * @param $errline = Linha do erro
     * @param $backtrace = ***
     */
    public static function handler($errno, $errstr, $errfile, $errline, $backtrace)
    {

        $const = self::ERRORS[$errno];
        //$const = ucfirst(strtolower(substr($const,strrpos($const,'_')+1)));
        $var['code']    = $errno;
        $var['name']    = $const;
        $var['message'] = $errstr;
        $var['file']    = $errfile;
        $var['line']    = $errline;
        //$var['trace'] = $backtrace;
        //ob_start();
        Container::_error(json_decode(json_encode($var)));
        //ob_end_flush();
        http_response_code(500);
        die;
    }
}