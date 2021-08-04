<?php
/**
 * Http запрос веб-приложения.
 * @package evas-php\evas-web
 * @author Egor Vasyakin <egor@evas-php.com>
 */
namespace Evas\Web;

use Evas\Base\App;
use Evas\Http\HttpRequest;

class WebRequest extends HttpRequest
{
    /**
     * Конструктор запроса.
     */
    public function __construct()
    {
        $request = self::createFromGlobals($this);
        if (($app = App::instance()) instanceof WebApp) {
            $request->withUri(
                str_replace($app->uri()->getPath(), '', $request->getUri())
            );
            $app::set('request', $this);
        }
        return $request;
    }
}
