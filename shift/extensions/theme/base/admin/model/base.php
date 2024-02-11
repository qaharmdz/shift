<?php

declare(strict_types=1);

namespace Shift\Extensions\Theme\Base\Admin\Model;

use Shift\System\Mvc;
use Shift\System\Helper;

class Base extends Mvc\Model {
    // List
    // ================================================

    /**
     * DataTables records
     *
     * @param  array  $params
     */
    public function dtRecords(array $params)
    {
        return __METHOD__;
    }
}
