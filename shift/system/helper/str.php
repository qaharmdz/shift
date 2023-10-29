<?php

declare(strict_types=1);

namespace Shift\System\Helper;

/**
 * String helper
 *
 * @link https://github.com/WordPress/wordpress-develop/blob/6.4/src/wp-includes/formatting.php
 */
class Str
{

    public static function htmlDecode(string $string, int $flags = ENT_QUOTES)
    {
        return html_entity_decode($string, $flags, 'UTF-8');
    }

    /**
     * Truncate by character length limit, return before closes break word
     *
     * @param  string $string
     * @param  int    $limit
     * @param  string $break
     * @param  string $ellipsis
     * @return string
     */
    public static function truncate(
        string $string,
        int $limit = 200,
        bool $stripTags = true,
        ?string $break = ' ',
        ?string $ellipsis = '...'
    ): string {
        if (strlen($string) <= $limit) {
            return $string;
        }

        $string = $stripTags ? strip_tags($string) : $string;
        $truncated = substr($string, 0, $limit);

        if ($break && strpos($truncated, $break)) {
            $truncated = substr($truncated, 0, strrpos($truncated, $break));

            if ($ellipsis) {
                $truncated .= $ellipsis;
            }
        }


        return $truncated;
    }
}
