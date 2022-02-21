<?php

declare(strict_types=1);

namespace Shift\System\core;

class Config extends Bags
{
    public function load(string $filename, string $group = '')
    {
        $file = DIR_SYSTEM . 'config' . DS . $filename . '.php';

        $data = [];
        if (is_file($file)) {
            $_ = [];
            require $file;

            $data = array_replace_recursive($data, $_);
        } else {
            throw new \InvalidArgumentException(sprintf('Unable to locate config file "%s".', $file));
        }

        $this->replaceRecursive($group ? [$group => $data] : $data);

        return $data;
    }
}
