<?php

declare(strict_types=1);

namespace Shift\Extensions\Module\Codex\Site\Controller;

use Shift\System\Mvc;

class Codex extends Mvc\Controller
{
    public function index(array $module = [])
    {
        if (!$module) {
            return null;
        }

        $setting  = json_decode($module['setting'], true);
        $template = trim(htmlspecialchars_decode(
            $setting['editor'],
            ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401
        ));

        $data = [
            'stringTemplate' => true,
            'codex' => [
                'user' => [
                    'user_id'       => $this->user->get('user_id'),
                    'user_group_id' => $this->user->get('user_group_id'),
                    'fullname'      => $this->user->get('fullname'),
                    'firstname'     => $this->user->get('firstname'),
                    'lastname'      => $this->user->get('lastname'),
                    'backend'       => $this->user->get('backend'),
                ],
            ],
        ];

        return $this->load->view($template, $data);
    }
}
