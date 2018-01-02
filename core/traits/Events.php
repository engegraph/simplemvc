<?php namespace Core\Traits;

/**
 * Implementação do ciclo de vida da aplicação. Útil para realizar oprações em determinados momentos da requisição.
 * Trait Events
 * @package Core\Traits
 * @author Airton Lopes <airton@engegraph.com.br>
 * @copyright Engegraph® Sistemas
 */

trait Events
{
    /**
     * Chamado ligeiramente com o construtor. Aqui ainda não tem nada pronto
     * @method onInit
     * @return void
     */
    public function onInit() : void {}

    /**
     * Chamado antes que uma action seja requisitada. Serviços e banco de dados já disponíveis neste ponto.
     * @method onRun
     * @return void
     */
    public function onRun() : void {}

    /**
     * Chamado antes de uma view ser renderizada. Mas tudo já está pronto.
     * @method onRender
     * @return void
     */
    public function onRender() : void {}

    /**
     * Chamado após a requisição ter sido finalizada e a página ter sido exibida
     * @method onEnd
     * @return void
     */
    public function onEnd() : void {}
}