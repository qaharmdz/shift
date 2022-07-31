<?php

declare(strict_types=1);

namespace Shift\Site\Model\Tool;

use Shift\System\Mvc;

class Image extends Mvc\Model
{
    public function resize($filename, $width, $height)
    {
        if (!is_file(DIR_MEDIA . $filename) || substr(str_replace('\\', DS, realpath(DIR_MEDIA . $filename)), 0, strlen(DIR_MEDIA)) != DIR_MEDIA) {
            return null;
        }

        $image_old     = $filename;
        $extension     = pathinfo($filename, PATHINFO_EXTENSION);
        $relative_file = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
        $image_new     = 'cache/' . str_replace(' ', '-', $relative_file) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;

        if (!is_file(DIR_MEDIA . $image_new) || (filectime(DIR_MEDIA . $image_old) > filectime(DIR_MEDIA . $image_new))) {
            list($width_orig, $height_orig, $image_type) = getimagesize(DIR_MEDIA . $image_old);

            if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
                return DIR_MEDIA . $image_old;
            }

            $path = '';

            $directories = explode('/', dirname($image_new));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir(DIR_MEDIA . $path)) {
                    @mkdir(DIR_MEDIA . $path, 0777);
                }
            }

            if ($width_orig != $width || $height_orig != $height) {
                $image = new \Shift\System\Library\Legacy\Image(DIR_MEDIA . $image_old);
                $image->resize($width, $height);
                $image->save(DIR_MEDIA . $image_new);
            } else {
                copy(DIR_MEDIA . $image_old, DIR_MEDIA . $image_new);
            }
        }

        return $this->config->get('env.url_site') . 'image/' . $image_new;
    }
}
