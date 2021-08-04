<?php
/**
 * Http ответ веб-приложения.
 * @package evas-php\evas-web
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Web;

use Evas\Http\HttpResponse;

class WebResponse extends HttpResponse
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
     * Применение свойств cookie.
     * @return self
     */
    protected function applyCookies()
    {
        if (!empty($this->cookies)) foreach ($this->cookies as &$cookie) {
            $this->withHeader('Set-Cookie', (string) $cookie);
        }
        return $this;
    }

    /**
     * Применение установленных заголовков вместе со статусом и куки.
     * @return self
     */
    public function applyHeaders()
    {
        $this->applyStatusCode();
        $this->applyCookies();
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        return $this;
    }
}
