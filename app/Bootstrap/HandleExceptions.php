<?php

namespace App\Bootstrap;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Bootstrap\HandleExceptions as BaseHandleExceptions;

/**
 * Laravel 8 turns every PHP notice into an exception, including E_DEPRECATED.
 * On PHP 8.1+ the old packages in vendor/ emit hundreds of deprecation notices,
 * which crashed every page. This keeps real errors as exceptions but ignores deprecations.
 */
class HandleExceptions extends BaseHandleExceptions
{
    public function bootstrap(Application $app)
    {
        parent::bootstrap($app);

        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
    }

    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        if ($level & (E_DEPRECATED | E_USER_DEPRECATED)) {
            return true;
        }

        return parent::handleError($level, $message, $file, $line, $context);
    }
}
