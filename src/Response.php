<?php
/**
 * @package evas-php\evas-web
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
     * Реализация реальной отправка ответа.
     */
    public function realSend()
    {
        $this->applyHeaders();
        echo $this->getBody();
    }

    /**
     * Применить код статуса.
     * @param int|null код статуса
     * @param string|null кастомный текст статуса
     * @return self
     */
    public function applyStatusCode(int $code = null, string $statusText = null)
    {
        if ($code) $this->withStatusCode($code, $statusText);
        header("HTTP/1.1 $this->statusCode $this->statusText");
        return $this;
    }

    /**
     * Применение установленных заголовков.
     * @return self
     */
    public function applyHeaders()
    {
        $this->applyStatusCode();
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
