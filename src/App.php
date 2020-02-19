<?php
/**
 * @package evas-php/evas-web
 */
namespace Evas\Web;

use Evas\Base\App as BaseApp;
use Evas\Web\Request;
use Evas\Web\Response;

/**
 * Класс веб-приложения.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class App extends BaseApp
{
    /**
     * @var string хост приложения
     */
    protected $host;

    /**
     * @var string базовый uri приложения
     */
    protected $uri;

    /**
     * @var string базовый путь приложения относительно document_root
     */
    protected $path;

    /**
     * @var string имя класса запроса
     */
    protected $requestClass = Request::class;

    /**
     * @var string имя класса ответа
     */
    protected $responseClass = Response::class;

    /**
     * @var RequestInterface объект запроса
     */
    protected $request;

    /**
     * @var ResponseInterface объект ответа
     */
    protected $response;

    /**
     * Установка хоста.
     * @param string
     * @return self
     */
    public static function setHost(string $host = null)
    {
        return static::instanceSet('host', $host);
    }

    /**
     * Установка базового uri приложения.
     * @param string|null
     * @return self
     */
    public static function setUri(string $uri = null)
    {
        return static::instanceSet('uri', $uri);
    }

    /**
     * Установка базового пути приложения относительно document_root.
     * @param string|null
     * @return self
     */
    public static function setPath(string $path = null)
    {
        return static::instanceSet('path', $path);
    }

    /**
     * Установка имени класса запроса.
     * @param string
     * @return self
     */
    public static function setRequestClass(string $requestClass)
    {
        return static::instanceSet('requestClass', $requestClass);
    }

    /**
     * Установка имени класса ответа.
     * @param string
     * @return self
     */
    public static function setResponseClass(string $responseClass)
    {
        return static::instanceSet('responseClass', $responseClass);
    }



    /**
     * Вычисление пути по директории относительно document_root.
     * @param string директория
     * @return string
     */
    public static function calcPath(string $dir = null): string
    {
        if (!$dir) $dir = static::getRunDir();
        $dir = str_replace('\\', '/', $dir);
        return substr(str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir), 0, -1);
    }


    /**
     * Получение базового пути uri приложения.
     * @return string
     */
    public static function getPath(): string
    {
        if (!static::instanceHas('path')) {
            static::setPath(static::calcPath());
        }
        return static::instanceGet('path');
    }

    /**
     * Получение хоста приложения.
     * @return string
     */
    public static function getHost(): string
    {
        if (!static::instanceHas('host')) {
            static::setHost($_SERVER['SERVER_NAME']);
        }
        return static::instanceGet('host');
    }

    /**
     * Получение базового uri ариложения.
     * @return string
     */
    public static function getUri(): string
    {
        if (!static::instanceHas('uri')) {
            $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
            // static::$_uri = "$protocol://$host" . (empty($path) ? '/' : $path);
            static::setUri("$protocol://" . static::getHost() . static::getPath());
        }
        return static::instanceGet('uri');
    }

    /**
     * Получение/установка базового хоста приложения.
     * @param string|null хост
     * @return string хост
     */
    public static function host(string $host = null): string
    {
        if ($host) static::setHost($host);
        return static::getHost();
    }

    /**
     * Получение/установка базового пути uri приложения.
     * @param string|null путь
     * @return string путь
     */
    public static function path(string $path = null): string
    {
        if ($path) static::setPath($path);
        return static::getPath();
    }

    /**
     * Получение/установка базового uri приложения.
     * @param string|null uri
     * @return string uri
     */
    public static function uri(string $uri = null): string
    {
        if ($uri) static::setUri($uri);
        return static::getUri();
    }

    /**
     * Получение запроса.
     * @return Request
     */
    public static function request()
    {
        if (!static::instanceHas('request')) {
            $requestClass = static::instanceGet('requestClass');
            $request = (new $requestClass)
                ->withUri(str_replace(static::getPath(), '', $_SERVER['REQUEST_URI']));
            static::instanceSet('request', $request);
        }
        return static::instanceGet('request');
    }

    /**
     * Получение ответа.
     * @return Response
     */
    public static function response()
    {
        if (!static::instanceHas('response')) {
            $responseClass = static::instanceGet('responseClass');
            $response = new $responseClass;
            static::instanceSet('response', $response);
        }
        return static::instanceGet('response');
    }
}
