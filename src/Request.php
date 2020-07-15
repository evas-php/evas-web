<?php
/**
 * @package evas-php\evas-web
 */
namespace Evas\Web;

use Evas\Http\Request as HttpRequest;

/**
 * Класс запроса веб-приложения.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class Request extends HttpRequest
{
    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this
            ->withMethod($_SERVER['REQUEST_METHOD'])
            ->withUri($_SERVER['REQUEST_URI'])
            ->withHeaders($this->getAllHeaders())
            ->withUserIp($_SERVER['REMOTE_ADDR'])
            ->withPost($_POST)
            ->withQuery($_GET)
            ->withBody(file_get_contents('php://input'));
    }

    /**
     * Получение заголовков запроса из getallheaders или $_SERVER.
     * @return array
     */
    public function getAllHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (preg_match('/^HTTP_(.+)$/', $name, $matches)) {
                $name = $matches[1];
                if ('REFERRER' === $name) $name = 'referer';
                $headers[$name] = $value;
            }
        }
        return $headers;
    }
}
