<?php

declare(strict_types=1);

namespace Shift\System\Helper;

class Date
{
    public static function now($zone = 'UTC', $format = 'Y-m-d H:i:s')
    {
        return (new \DateTime('now', new \DateTimeZone($zone)))->format($format);
    }

    public static function format($date, $format = 'Y-m-d H:i:s')
    {
        return (new \DateTime($date))->format($format);
    }

    public static function modify($date, $modify = '+1 day', $format = 'Y-m-d H:i:s')
    {
        return (new \DateTime($date))->modify($modify)->format('Y-m-d H:i:s');
    }

    public static function fromUtc($date, $toZone, $format = 'Y-m-d H:i:s')
    {
        return (new \DateTime($date, new \DateTimeZone('UTC')))
            ->setTimeZone(new \DateTimeZone($toZone))
            ->format($format);
    }

    public static function toUtc($date, $fromZone, $format = 'Y-m-d H:i:s')
    {
        return (new \DateTime($date, new \DateTimeZone($fromZone)))
            ->setTimeZone(new \DateTimeZone('UTC'))
            ->format($format);
    }
}
