<?php

declare(strict_types=1);

namespace Shift\System\Core\Mvc;

class View
{
    public function render($template, array $vars = [])
    {
        if (APP_FOLDER == 'site') {
            $template = 'base/template/' . $template;
        }

        $file = DIR_TEMPLATE . $template;

        if (is_file($file)) {
            extract($vars);

            ob_start();

            require($file);

            return ob_get_clean();
        }

        trigger_error('Error: Could not load template ' . $file . '!');
        exit();
    }
}
