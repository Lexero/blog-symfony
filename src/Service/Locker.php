<?php

declare(strict_types=1);

namespace App\Service;

use Redis;

readonly class Locker
{
    public function __construct(private \Redis $redis)
    {
    }

    public function getLockedBy(string $key): ?int
    {
        $userId = $this->redis->get($key);
        if ($userId === false) {
            return null;
        }
        return (int)$userId;
    }

    public function setLock(string $key, int $userId, int $ttl = 300): void
    {
        $this->redis->set($key, $userId, $ttl);
    }

    public function refreshLock(string $key, int $userId, int $ttl = 300): void
    {
        if ($this->getLockedBy($key) === $userId) {
            $this->redis->expire($key, $ttl);
        }
    }
}
