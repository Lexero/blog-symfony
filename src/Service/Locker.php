<?php

namespace App\Service;

use Redis;

class Locker
{
    private Redis $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
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
        if ($this->getLockedBy($key) === $userId){
            $this->redis->expire($key, $ttl);
        }
    }
}