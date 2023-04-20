<?php

declare(strict_types=1);

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'utf8.php';

if (!function_exists('sanitizeChar')) {
    /**
     * Sanitize characters
     *
     * ex: [1]<>+=_`~ !–@#$;"\'\%   ^&*(\{)?}/2=\ -,./../*:|3    result: 1-_-2-3
     *
     * @return string
     */
    function sanitizeChar($string, $glue = '-', $trim = '_-.'): string
    {
        return trim(
            preg_replace(
                '/[\>\<\+\?\&\"\'\`\/\\\:\;\s\–\-\,\.\{\}\(\)\[\]\~\!\@\^\*\|\$\#\%\=\r\n\t]+/',
                $glue,
                $string
            ),
            $trim
        );
    }
}
