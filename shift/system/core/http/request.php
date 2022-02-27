<?php

declare(strict_types=1);

namespace Shift\System\Core\Http;

use Shift\System\Core;

/**
 * Represents a HTTP request.
 */
class Request extends Core\Bags
{
    public function __construct()
    {
        $this->set([
            'query'   => $_GET     = $this->clean($_GET),
            'post'    => $_POST    = $this->clean($_POST),
            'cookie'  => $_COOKIE  = $this->clean($_COOKIE),
            'files'   => $_FILES   = $this->clean($_FILES),
            'server'  => $_SERVER  = $this->clean($_SERVER),
            'request' => $_REQUEST = $this->clean($_REQUEST)
        ]);
    }

    public function method(): string
    {
        return strtoupper($this->get('server.REQUEST_METHOD'));
    }

    public function is($type): bool
    {
        if (is_array($type)) {
            $valid = true;

            foreach ($type as $check) {
                if (!$valid = $this->is($check)) {
                    break;
                }
            }

            return $valid;
        }

        switch (strtolower($type)) {
            case 'post':
                return $this->method() == 'POST' ? true : false;

            case 'get':
                return $this->method() == 'GET' ? true : false;

            case 'put':
                return $this->method() == 'PUT' ? true : false;

            case 'delete':
                return $this->method() == 'DELETE' ? true : false;

            case 'secure': // SSL
                return $this->get('server.SECURE', 0) ? true : false;

            case 'ajax':
                return strtolower($this->get('server.HTTP_X_REQUESTED_WITH', '')) === 'xmlhttprequest' ? true : false;

            case 'json':
                return str_contains($this->get('server.HTTP_ACCEPT', ''), 'application/json');

            case 'html':
                return str_contains($this->get('server.HTTP_ACCEPT', ''), 'text/html');

            case 'cli':
                return (PHP_SAPI === 'cli' || defined('STDIN'));

            default:
                return false;
        }
    }

    public function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);
                $data[$this->clean($key)] = $this->clean($value);
            }
        } elseif (is_string($data)) {
            $data = trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
        }

        return $data;
    }

    public function getIp(): string
    {
        return getenv('HTTP_CLIENT_IP') ?: (
            getenv('HTTP_X_FORWARDED_FOR') ?: (
                getenv('HTTP_X_FORWARDED') ?: (
                    getenv('HTTP_FORWARDED_FOR') ?: (
                        getenv('HTTP_FORWARDED') ?: (
                            getenv('REMOTE_ADDR') ?: $this->get('server.REMOTE_ADDR')
                        )
                    )
                )
            )
        );
    }
}
