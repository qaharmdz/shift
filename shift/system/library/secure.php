<?php

declare(strict_types=1);

namespace Shift\System\Library;

class Secure {

    protected $algo;

    public function __construct(string|int $algo = PASSWORD_DEFAULT)
    {
        $this->algo = $this->isValidAlgo($algo) ? $algo : PASSWORD_DEFAULT;
    }

    /**
     * Check if given type is valid algorithm.
     *
     * @param  string|int|null    $type
     * @return boolean
     */
    public function isValidAlgo(string|int|null $type): bool
    {
        return in_array($type, password_algos()) || in_array($type, hash_algos());
    }

    /**
     * Password hash from string.
     *
     * @param  string $password
     * @return string
     */
    public function passwordHash(string $password): string
    {
        return password_hash($password, $this->algo);
    }

    /**
     * Verify given password.
     *
     * @param  string  $password
     * @param  string  $hash
     * @return bool
     */
    public function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Check if password hash need to be updated.
     *
     * @param  string  $hash
     * @return bool
     */
    public function isPasswordNeedRehash(string $hash): bool
    {
        return password_needs_rehash($hash, $this->algo);
    }

    /**
     * Hash
     *
     * @param  string $string
     * @param  string $type
     * @return string
     */
    public function hash(string $string, string $type = 'sha256'): string
    {
        $type = $this->isValidAlgo($type) ? $type : 'sha256';

        return hash($type, $string, false);
    }

    /**
     * Crypt
     *
     * @param  int $length
     * @return string
     */
    public function crypt(int $length = 0): string
    {
        $length = $length ?: rand(32, 64);
        $length = (int) ceil($length / 2);

        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length);
        } elseif (function_exists('random_bytes')) {
            $bytes = random_bytes($length);
        } else {
            throw new \Exception('No cryptographically secure random function available!');
        }

        return bin2hex($bytes);
    }

    /**
     * Random
     *
     * @param int $length
     * @return string
     */
    public function random(int $length = 0): string
    {
        return $this->crypt($length);
    }


    /**
     * Generate token code.
     *
     * @param  string      $type
     * @param  integer     $length
     * @return string
     */
    public function token(string $type = 'hash', int $length = 32): string
    {
        switch ($type) {
            case 'basic':
                $result = (string) mt_rand();
                break;

            case 'numeric':
                $pool = '0123456789';
                $result = str_shuffle(str_repeat($pool, (int) ceil($length / strlen($pool))));
                break;

            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $result = str_shuffle(str_repeat($pool, (int) ceil($length / strlen($pool))));
                break;

            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $result = str_shuffle(str_repeat($pool, (int) ceil($length / strlen($pool))));
                break;

            case 'crypt':
                $result = $this->crypt($length);
                break;

            case 'hash':
            default:
                $result = $this->hash((string) mt_rand());
                break;
        }

        return substr($result, rand(0, (strlen($result) - $length)), $length);
    }
}
