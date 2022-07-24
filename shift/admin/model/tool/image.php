<?php

declare(strict_types=1);

namespace Shift\Admin\Model\Tool;

use Shift\System\Mvc;

class Image extends Mvc\Model
{
    public function resize($filename, $width, $height)
    {
        if (!is_file(DIR_IMAGE . $filename) || substr(str_replace('\\', DS, realpath(DIR_IMAGE . $filename)), 0, strlen(DIR_IMAGE)) != DIR_IMAGE) {
            return null;
        }

        $image_old     = $filename;
        $extension     = pathinfo($filename, PATHINFO_EXTENSION);
        $relative_file = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
        $image_new     = 'cache/' . str_replace(' ', '-', $relative_file) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

        if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
            list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

            if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
                return DIR_IMAGE . $image_old;
            }

            $path = '';

            $directories = explode('/', dirname($image_new));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image(DIR_IMAGE . $image_old);
                $image->resize($width, $height);
                $image->save(DIR_IMAGE . $image_new);
            } else {
                copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
            }
        }

        return $this->config->get('env.url_site') . 'image/' . $image_new;
    }
}
