<?php

declare(strict_types=1);

namespace Shift\System\Core;

class Session extends Bags
{
    public function __construct(array $config = [])
    {
        if ($this->getId() !== '') {
            $this->destroy();
        }

        $config = array_replace_recursive([
            'session_name'  => 'SESSID',
            'use_cookies'   => '1',
            'use_trans_sid' => '0',
            'sid_length'    => 48
        ], $config);

        ini_set('session.cookie_httponly', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_strict_mode', '1');
        ini_set(
            'session.cookie_domain',
            substr($_SERVER['SERVER_NAME'], (int)strpos($_SERVER['SERVER_NAME'], '.'))
        );

        if ($_SERVER['SECURE']) {
            ini_set('session.cookie_secure', '1');
        }

        ini_set('session.use_cookies', (string)$config['use_cookies']);
        ini_set('session.use_trans_sid', (string)$config['use_trans_sid']);
        ini_set('session.sid_length', (string)$config['sid_length']);

        session_set_cookie_params(0, '/');
        session_name($config['session_name']);

        session_start();

        $this->setReference($_SESSION);
        $this->add('flash', []);
    }

    public function getId(): string
    {
        return session_id();
    }

    /**
     * Regenerate seesion_id
     *
     * @param  bool   $delete   Delete old session
     */
    public function regenerateId(bool $delete = false)
    {
        if (session_status() === \PHP_SESSION_ACTIVE) {
            session_regenerate_id($delete);
        }
    }

    /**
     * Deleting the whole session.
     */
    public function destroy(bool $restart = true)
    {
        $data = session_get_cookie_params();

        setcookie(session_name(), '', time() - 42000, $data['path'], $data['domain'], $data['secure'], $data['httponly']);
        session_destroy();

        if ($restart) {
            session_start();
        }
    }
}
