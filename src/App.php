<?php
/**
 * @package evas-php\evas-web
 */
namespace Evas\Web;

use Evas\Base\App as BaseApp;
use Evas\Web\Request;
use Evas\Web\Response;

/**
 * Константы для свойств класса по умолчанию.
 */
if (!defined('EVAS_WEB_REQUEST_CLASS')) define('EVAS_WEB_REQUEST_CLASS', Request::class);
if (!defined('EVAS_WEB_RESPONSE_CLASS')) define('EVAS_WEB_RESPONSE_CLASS', Response::class);


/**
 * Класс веб-приложения.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
class App extends BaseApp
{
    /**
     * Установка хоста.
     * @param string
     * @return self
     */
    public static function setHost(string $host = null)
    {
        return static::set('host', $host);
    }

    /**
     * Установка базового uri приложения.
     * @param string|null
     * @return self
     */
    public static function setUri(string $uri = null)
    {
        return static::set('uri', $uri);
    }

    /**
     * Установка базового пути приложения относительно document_root.
     * @param string|null
     * @return self
     */
    public static function setPath(string $path = null)
    {
        return static::set('path', $path);
    }

    /**
     * Установка имени класса запроса.
     * @param string
     * @return self
     */
    public static function setRequestClass(string $requestClass)
    {
        return static::set('requestClass', $requestClass);
    }

    /**
     * Установка имени класса ответа.
     * @param string
     * @return self
     */
    public static function setResponseClass(string $responseClass)
    {
        return static::set('responseClass', $responseClass);
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
        if (!static::has('path')) {
            static::setPath(static::calcPath());
        }
        return static::get('path');
    }

    /**
     * Получение хоста приложения.
     * @return string
     */
    public static function getHost(): string
    {
        if (!static::has('host')) {
            static::setHost($_SERVER['SERVER_NAME']);
        }
        return static::get('host');
    }

    /**
     * Получение базового uri ариложения.
     * @return string
     */
    public static function getUri(): string
    {
        if (!static::has('uri')) {
            $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
            static::setUri("$protocol://" . static::getHost() . static::getPath());
        }
        return static::get('uri');
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
     * Получение имени класса запроса.
     * @return string
     */
    public static function getRequestClass(): string
    {
        if (!static::has('requestClass')) {
            static::set('requestClass', EVAS_WEB_REQUEST_CLASS);
        }
        return static::get('requestClass');
    }

    /**
     * Получение имени класса ответа.
     * @return string
     */
    public static function getResponseClass(): string
    {
        if (!static::has('responseClass')) {
            static::set('responseClass', EVAS_WEB_RESPONSE_CLASS);
        }
        return static::get('responseClass');
    }

    /**
     * Получение запроса.
     * @return Request
     */
    public static function request(): object
    {
        if (!static::has('request')) {
            $requestClass = static::getRequestClass();
            $request = (new $requestClass)
                ->withUri(str_replace(static::getPath(), '', $_SERVER['REQUEST_URI']));
            static::set('request', $request);
        }
        return static::get('request');
    }

    /**
     * Получение ответа.
     * @return Response
     */
    public static function response(): object
    {
        if (!static::has('response')) {
            $responseClass = static::getResponseClass();
            $response = new $responseClass;
            static::set('response', $response);
        }
        return static::get('response');
    }
}
