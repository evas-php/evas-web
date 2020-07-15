<?php
/**
 * @package evas-php\evas-web
 */
namespace Evas\Web;

use Evas\Router\Controller as RouterController;
use Evas\Web\App;

/**
 * Класс контроллера веб-приложения.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class Controller extends RouterController
{
	/**
     * @var RequestInterface
     */
    public $request;

    /**
     * @var ResponseInterface
     */
    public $response;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->request = App::request();
        $this->response = App::response();
    }
}
