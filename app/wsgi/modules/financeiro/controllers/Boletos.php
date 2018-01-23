<?php namespace wSGI\Modules\Financeiro\Controllers;

use Core\Controller;

/**
 * Class Boletos Exemplo hipotético de como poderia ser renderização de boletos
 * @package wSGI\Modules\Financeiro\Controllers
 */

class Boletos extends Controller
{
    public $model = null;

    /**
     * @var $Codigo string Código do boleto a ser exibido
     */
    public $Codigo;


    public function onRun(): void
    {
        /**
         * Pegando o parâmetro código diretamente da url
         * Com o código recuperado pode-se fazer as consultas no banco para pegar informações do boleto
         */
        $codigo = $this->Request->Params[0]; # Ou siplesmente $this->Request->Params[0]
        $this->Codigo = 'Código direto da URL: '.$codigo;
    }


    /**
     * Método visualizar recebe um parâmetro como número do boleto
     * @param $codigo
     */
    public function visualizar($codigo)
    {
        /*
        *
         * Recebendo o código por parâmetro do método
         * Com o código recuperado pode-se fazer as consultas no banco para pegar informações do boleto
         */
        $this->Codigo = 'Código através do método: '.$codigo;

        //
        // Método view é responsável por renderizar as páginas do sistema
        // Recebe dois parâmetros, sendo o primeiro o nome da view a ser renderizada.
        // O nome da view pode ter pontos como sendo separadores de diretórios.
        // O Segundo parâmetro é o nome do layout a ser utilizado como container para a view, se for omitido, por
        // padrão o sistema usa o layout default da pasta referente ao template definido em config/application
        // Mas pode-se usar outro layout, desde que o mesmo seja criado no diretório layouts.
        // Caso necessário pode-se definir o parâmetro layout como false, indicando que não deseja usar nenhum layout.
        // Dessa forma a view será renderizada sozinha. Mas, é importante pontuar que ao usar as views dessa maneira,
        // todos os estilos css, arquivos e funções javascripts não estarão disponíveis, uma vez que a view está sendo
        // renderizada isoladamente. Se desejar ter todas as funcionalidades disponíveis, o mais correto seria criar
        // um novo layout específico e dentro deste inserir todas as chamadas de estilos, funções, exatamente como é
        // o layout principal. Daí bastaria chamar a view dentro do seu novo layout recem-criado.
        //
        $this->view('boletos.aluguel', false);

    }
}