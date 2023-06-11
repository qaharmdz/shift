<?php

declare(strict_types=1);

namespace Shift\Extensions\Module\Codex\Site\Controller;

use Shift\System\Mvc;

class Codex extends Mvc\Controller
{
    public function index(array $config = [])
    {
        if (!$config) {
            return null;
        }

        $this->load->model('extensions/module/codex');

        $setting  = json_decode($config['setting'], true);
        $template = trim(htmlspecialchars_decode(
            $setting['editor'],
            ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401
        ));

        $data = [
            'stringTemplate' => true,
            'codex' => $this->model_extensions_module_codex,
        ];

        return $this->load->view($template, $data);
    }
}
