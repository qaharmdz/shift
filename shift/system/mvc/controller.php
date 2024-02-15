<?php

declare(strict_types=1);

namespace Shift\System\Mvc;

use Shift\System\Core;

/**
 * List of properties added through the system Framework
 *
 * @property \Shift\System\Http\Request         $request
 * @property \Shift\System\Http\Response        $response
 * @property \Shift\System\Http\Router          $router
 * @property \Shift\System\Core\Config          $config
 * @property \Shift\System\Core\Database        $db
 * @property \Shift\System\Core\Session         $session
 * @property \Shift\System\Core\Event           $event
 * @property \Shift\System\Core\Logger          $log
 * @property \Shift\System\Core\Loader          $load
 * @property \Shift\System\Library\Secure       $secure
 * @property \Shift\System\Library\Assert       $assert
 * @property \Shift\System\Library\Language     $language
 * @property \Shift\System\Library\Document     $document
 * @property \Shift\System\Library\Cache        $cache
 * @property \Shift\System\Library\Mail         $mail
 * @property \Shift\System\Library\Image        $image
 * @property \Shift\System\Library\User         $user
 */
abstract class Controller {
    protected Core\Registry $registry;

    public function __construct()
    {
        $this->registry = Core\Registry::init();
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }
}
