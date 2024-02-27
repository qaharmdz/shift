<?php

declare(strict_types=1);

namespace Shift\Extensions\Module\Codex\Site\Controller;

use Shift\System\Mvc;

class Codex extends Mvc\Controller {
    public function index(array $config = [])
    {
        // Prevent route access
        // https://example.com/index.php?route=extensions/module/codex
        if (!$config) {
            return null;
        }

        $this->load->model('extensions/module/codex');

        $setting = json_decode($config['setting'], true);
        $template = trim(
            htmlspecialchars_decode(
                $setting['editor'],
                ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401
            )
        );

        // TODO: auto refresh fragment-cache(?) https: //twig.symfony.com/doc/3.x/tags/cache.html
        /*
        $template = '{% cache "codex.[:id].[:updated]" %}' . $template . '{% endcache %}';
        */

        $data = [
            '_strTwig' => true,
            'codex'    => $this->model_extensions_module_codex,
        ];

        return $this->load->view($template, $data);
    }
}
