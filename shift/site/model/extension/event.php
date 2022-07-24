<?php

declare(strict_types=1);

namespace Shift\Site\Model\Extension;

use Shift\System\Mvc;

class Event extends Mvc\Model
{
    function getEvents()
    {
        $query = $this->db->get("SELECT * FROM `" . DB_PREFIX . "event` WHERE `trigger` LIKE 'catalog/%' AND status = '1' ORDER BY `event_id` ASC");

        return $query->rows;
    }
}
