<?php

declare(strict_types=1);

namespace Session;

class File extends \SessionHandler
{
    public function create_sid(): string
    {
        return parent::create_sid();
    }

    public function open($path, $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $session_id): string|false
    {
        $file = session_save_path() . '/sess_' . $session_id;

        if (is_file($file)) {
            $handle = fopen($file, 'r');

            flock($handle, LOCK_SH);

            $data = fread($handle, filesize($file));

            flock($handle, LOCK_UN);

            fclose($handle);

            return $data;
        }

        return null;
    }

    public function write(string $session_id, string $data): bool
    {
        $file = session_save_path() . '/sess_' . $session_id;

        $handle = fopen($file, 'w');

        flock($handle, LOCK_EX);

        fwrite($handle, $data);

        fflush($handle);

        flock($handle, LOCK_UN);

        fclose($handle);

        return true;
    }

    public function destroy(string $session_id): bool
    {
        $file = session_save_path() . '/sess_' . $session_id;

        if (is_file($file)) {
            unset($file);
        }
    }

    public function gc(int $maxlifetime): int|false
    {
        return parent::gc($maxlifetime);
    }
}
