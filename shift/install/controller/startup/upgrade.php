<?php

declare(strict_types=1);

namespace Shift\Install\Controller\Startup;

use Shift\System\Mvc;

class Upgrade extends Mvc\Controller
{
    public function index()
    {
        $upgrade = false;

        if (is_file(PATH_SHIFT . 'config.php') && filesize(PATH_SHIFT . 'config.php') > 0) {
            $upgrade = true;
        }

        if ($this->request->has('query.route')) {
            if (($this->request->get('query.route') == 'install/step_4') || (substr($this->request->get('query.route'), 0, 8) == 'upgrade/') || (substr($this->request->get('route'), 0, 10) == '3rd_party/')) {
                $upgrade = false;
            }
        }

        if ($upgrade) {
            $this->response->redirect($this->router->url('upgrade/upgrade'));
        }
    }
}
