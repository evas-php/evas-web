<?php
/**
 * Класс веб-приложения.
 * @package evas-php\evas-web
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Web;

use \InvalidArgumentException;
use Evas\Base\App as BaseApp;
use Evas\Base\Help\PhpHelp;
use Evas\Base\Loader;
use Evas\Http\Uri;
use Evas\Http\Interfaces\RequestInterface;
use Evas\Http\Interfaces\ResponseInterface;
use Evas\Web\WebRequest;
use Evas\Web\WebResponse;

class WebApp extends BaseApp
{
    /**
     * Установка Uri приложения.
     * @param Uri|string uri приложения
     * @return static
     * @throws InvalidArgumentException
     */
    public static function setUri($uri): BaseApp
    {
        if (is_string($uri)) {
            $uri = new Uri($uri);
        } else if (!($uri instanceof Uri)) {
            throw new InvalidArgumentException(sprintf(
                'The type of the application uri must be a string or an instance of the %s, 
                %s given',
                Uri::class,
                PhpHelp::getType($uri)
            ));
        }
        if (empty($uri->getHost())) {
            $uri->withHost($_SERVER['SERVER_NAME']);
        }
        if (empty($uri->getPort())) {
            $uri->withPort($_SERVER['SERVER_PORT']);
        }
        if (empty($uri->getScheme())) {
            $uri->withScheme(empty($_SERVER['HTTPS']) ? 'http' : 'https');
        }
        return static::set('uri', $uri);
    }

    /**
     * Вычисление пути uri приложения относительно директории запуска и document_root.
     * @param string|null директория запуска
     * @param string|null директория корня сервера
     * @return string
     */
    protected static function calcPath(string $runDir = null, string $documentRoot = null): string
    {
        if (!$runDir) $runDir = Loader::getRunDir();
        if (!$documentRoot) $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        $runDir = str_replace('\\', '/', $runDir);
        return substr(str_replace($documentRoot, '', $runDir), 0, -1);
    }

    /**
     * Получение uri приложения.
     * @return Uri
     */
    public static function uri(): Uri
    {
        if (!static::has('uri')) {
            static::setUri(static::calcPath());
        }
        return static::get('uri');
    }

    /**
     * Получение объекта запроса приложения.
     * @return RequestInterface
     */
    public static function request(): RequestInterface
    {
        if (!static::has('request')) {
            $instance = static::instance();
            static::set('request', new WebRequest($instance));
        }
        return static::get('request');
    }

    /**
     * Установка запроса веб-приложения.
     * @param RequestInterface
     * @return self
     */
    public static function setRequest(RequestInterface &$request)
    {
        return static::set('request', $request);
    }

    /**
     * Получение объекта ответа приложения.
     * @return ResponseInterface
     */
    public static function response(): ResponseInterface
    {
        if (!static::has('response')) {
            static::set('response', new WebResponse);
        }
        return static::get('response');
    }

    /**
     * Редирект относительно приложения.
     * @param string адрес редиректа
     */
    public static function redirectByApp(string $to)
    {
        $to = static::uri() . $to;
        return static::response()->redirect($to);
    }
}
