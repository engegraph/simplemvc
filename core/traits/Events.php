<?php namespace Core\Traits;

/**
 * Implementação de controle do ciclo de vida da aplicação. Útil para realizar oprações em determinados momentos da requisição.
 * Trait Events
 * @package Core\Traits
 * @author Airton Lopes <airton.lopes@engegraph.com.br>
 * @copyright Engegraph® Sistemas
 */

trait Events
{
    /**
     * Chamado ligeiramente após o Front Controller ter identificado a rota e instanciado o controller específico. Aqui ainda não tem nada pronto
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
     * Chamado quando uma view está prestes a ser renderizada
     * @method onRender
     * @return void
     */
    public function onRender() : void {}

    /**
     * Chamado após a requisição ter sido finalizada e a página ter sido exibida com sucesso
     * @method onEnd
     * @return void
     */
    public function onEnd() : void {}
}