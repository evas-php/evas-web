<?php
/**
 * @package evas-php/evas-web
 */
namespace Evas\Web;

use Evas\Http\Response as HttpResponse;
use Evas\Web\App;

/**
 * Класс ответа веб-приложения.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class Response extends HttpResponse
{
    /**
     * Переопределение отправки ответа.
     * @param int|null код статуса
     * @param string|null тело
     * @param array|null заголовки
     */
    public function send(int $code = null, string $body = null, array $headers = null)
    {
        parent::send($code, $body, $headers);
        $this->applyHeaders();
        echo $this->getBody();
    }

    /**
     * Применение установленных заголовков.
     * @return self
     */
    public function applyHeaders()
    {
        header("HTTP/1.1 $this->statusCode $this->statusText");
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        return $this;
    }

    /**
     * Переопределение редиректа для подстановки uri приложения.
     * @param string куда
     */
    public function redirect(string $to)
    {
        if (strpos($to, 'http') !== 0) {
            $to = App::getUri() . $to;
        }
        return parent::redirect($to);
    }
}
