<?php

declare(strict_types=1);

namespace Shift\Extensions\Module\Codex\Site\Model;

use Shift\System\Mvc;

class Codex extends Mvc\Model {
    public function getUser(string $key, $default = null)
    {
        return $this->user->get($key, $default);
    }

    public function getContentPost()
    {
        //
    }
}
