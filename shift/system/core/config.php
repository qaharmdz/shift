<?php

declare(strict_types=1);

namespace Shift\System\Core;

class Config extends Bags {
    public function load(string $filename, string $group = '')
    {
        $file = PATH_SYSTEM . 'config' . DS . $filename . '.php';

        if (str_starts_with($filename, 'extensions/')) {
            $file = PATH_SHIFT . $filename . DS . 'config.php';
        }

        $data = [];
        if (is_file($file)) {
            $_ = [];
            require $file;

            $data = array_replace_recursive($data, $_);
        } else {
            throw new \InvalidArgumentException(sprintf('Unable to locate config file "%s".', $file));
        }

        if ($group) {
            $data = $this->set('_temp.' . $group, $data)->get('_temp');
            $this->delete('_temp');
        }

        $this->replaceRecursive($data);

        return $data;
    }
}
