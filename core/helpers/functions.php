<?php

/**
 * Helpers
 * Funções e constantes diversas para auxilio
 * @param null $str
 * @return string
 */

function url($str = null)
{
    $str = $str ? $str : $_SERVER['REQUEST_URI'];
    $s = ($rsc = $_SERVER['REQUEST_SCHEME']) ? $rsc : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $url = "{$s}://{$host}{$str}";
    return rtrim($url, '/');
}

function backend_url($url)
{
    global $app;
    $url = url("/{$app['backend']}{$url}");
    return rtrim($url, '/');
}

function active_item($url)
{
    if ($url == url())
        return 'active';
}

function tpl_assets($str = null)
{
    global $app;
    $path = url('/app/templates/'.$app['template'].'/assets/');
    return ($str ? "{$path}/{$str}" : $path);
}

function path_assets(string $file = null)
{
    $path = url('/app/modules/' . (defined('ADMIN') ? 'wsgi' : 'site') . '/assets');
    return ($file ? $path.'/'.$file : $path);
}

function path_storage(string $file = null)
{

    $str = __DIR__ . '/../storage/';
    return $file ? $str.$file : $str;
}

function get($name, $default = null)
{
    if(in_array($name,['Controller','Action','Params']))
    {
        $Prop = 'raw'.$name;
        return \Core\Request::$$Prop;
    }
}


function post($name = null, $default = null)
{

    $data = $_POST;

    if (!$name && !$default)
        return $data;

    $wrap = function ($name) {
        $str = "['";
        if ($count = substr_count($name, '.')) {
            $exp = explode('.', $name);
            $imp = implode("']['", $exp);
            $name = $imp;
        }
        $str .= $name;
        $str .= "']";
        return $str;
    };

    $index = $wrap($name);

    return eval('return isset($data' . $index . ') ?  $data' . $index . ' : ($default ? $default : NULL);');
}

function guid()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid());

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    $str = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    return "{{$str}}";
}

function str_guid($str, $guid = false)
{
    if (!$guid) {
        $str = strtolower(str_replace('-', '', $str));
        return "_{$str}";
    } else {
        $s = '';
        for ($i = 1; $i < strlen($str); $i++) {
            $s .= strtoupper($str{$i});
            if ($i == 8 || $i == 12 || $i == 16 || $i == 20)
                $s .= '-';
        }
        return $s;
    }
}

function is_guid($str)
{
    $pattern = '/^[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}$/i';
    if(preg_match($pattern, $str))
    {
        $string = str_replace('-','', $str);
        $len = strlen($string);
        $var = '';
        for($i=0; $i < $len; $i++)
        {
            if( $i==8 || $i==12 || $i==16 || $i==20 || $i==32 )
                $var .= '-';

            $var .= $string{$i};
        }

        if($var===$str)
            return true;
    }
}

function eval_arr(&$data, $name, $val = null)
{

    $name = str_replace('[', '.', trim($name, '[]'));
    $name = str_replace(']', '', $name);

    $str = "['";
    if ($count = substr_count($name, '.')) {
        $exp = explode('.', $name);
        $imp = implode("']['", $exp);
        $name = $imp;
    }
    $str .= $name;
    $str .= "']" . ' = $val;';
    eval('$data' . $str);
}

function array_deep_inverse(array &$Data, $Chave = null, $Res = [])
{
    if (sizeof($Data)) {
        $Attr = [];
        foreach ($Data as $Name => $Val) {
            if (is_array($Val))
                $Res = array_deep_inverse($Val, $Name, $Res);
            else
                $Attr[$Name] = $Val;
        }
        if ($Chave) $Res[$Chave] = $Attr;
    }
    return $Data = $Res;
}

function array_kshift(&$arr)
{
    list($k) = array_keys($arr);
    $r = array($k => $arr[$k]);
    unset($arr[$k]);
    return $r;
}


function array_to_literal_object($Data, $Var = '$o', $Str = '', $Key = '', $Level = 0)
{
    if (sizeof($Data)) {
        foreach ($Data as $Name => $Value) {
            if (is_array($Value)) {
                $Level++;
                $Str = array_to_literal_object($Value, $Var . '->' . $Name, $Str, $Name, $Level);
            } else {
                $Str .= $Var . '->' . $Name;
                $Str .= " = '{$Value}'; \n";
            }
        }
        return $Str;
    }
}

;

//Funções da data
function month_add($data, $months = 1)
{
    return date('Y-m-d', strtotime("+$months month", strtotime($data)));
}

function is_weekend($date)
{
    $weekDay = date('w', strtotime($date));
    return ($weekDay == 0 || $weekDay == 6);
}

function next_util_day($data)
{
    while (is_weekend($data)) {
        $data = date('Y-m-d', strtotime("+1 day", strtotime($data)));
    }
    return $data;
}

//Funções d mascara
/*
mask($cnpj,'##.###.###/####-##');
mask($cpf,'###.###.###-##');
mask($cep,'#####-###');
mask($data,'##/##/####');
*/
function mask($val, $mask)
{
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        } else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}


function alias($string)
{
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}

function is_sequential (array $array, bool $strict = false) {
    return array_filter($array, "is_int", ARRAY_FILTER_USE_KEY) == ($strict ? $array : true);
}

function is_associative (array $array, bool $strict = false) {
    return array_filter($array, "is_string", ARRAY_FILTER_USE_KEY) == ($strict ? $array : true);
}

function labelBit($get_valor, $cor_sim = 'success', $cor_nao = 'danger', $txt_sim = 'Sim', $txt_nao = 'Não') {
    return ($get_valor == 1) ? '<span class="label label-' . $cor_sim . '">' . $txt_sim . '</span>' : '<span class="label label-' . $cor_nao . '">' . $txt_nao . '</span>';
}

function RetiraCaracteres($valor){
    $pontos = array(",", ".", "/", "-");
    $result = str_replace($pontos, "", $valor);
    return $result;
}

function array_insert_pos(array &$input, $item, $pos = null)
{
    if($pos)
    {
        $pos = $pos==0 ? 1 : $pos;
        $pos--;
        $list_1 = array_slice($input, 0, $pos);
        $list_1[] = $item;
        $list_2 = array_slice($input, $pos);
        $input = array_merge($list_1, $list_2);
    }
    else
        $input[] = $item;
}
