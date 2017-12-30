<?php

/**
 * wSGI Application = Sistema para imobiliárias
 * wsgi.engegraph.com.br/jota
 * wsgi.engegraph.com.br/jota/imoveis/listagem
 */

/**
 * Arquivo autoload do composer para inclusão automática das classes
 */
require __DIR__.'/vendor/autoload.php';

/**
 * Carregando tudo enquanto
 */
require __DIR__.'/config/bootstrap.php';

/**
 * Instanciando a aplicação principal
 */
$App = new App\wSGI();

/*
 * Rodando
 */
$App->run();