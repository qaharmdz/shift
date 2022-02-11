<?php

declare(strict_types=1);

namespace Session;

class Native extends \SessionHandler
{
    public function create_sid(): string
    {
        return parent::create_sid();
    }

    public function open($path, $name): bool
    {
        return parent::open($path, $name);
    }

    public function close(): bool
    {
        return parent::close();
    }

    public function read(string $session_id): string|false
    {
        return parent::read($session_id);
    }

    public function write(string $session_id, string $data): bool
    {
        return parent::write($session_id, $data);
    }

    public function destroy(string $session_id): bool
    {
        return parent::destroy($session_id);
    }

    public function gc(int $maxlifetime): int|false
    {
        return parent::gc($maxlifetime);
    }
}
