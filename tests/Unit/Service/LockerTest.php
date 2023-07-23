<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\Locker;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Redis;

class LockerTest extends WebTestCase
{
    public function testGetLockedBy()
    {
        $redis = $this->createMock(Redis::class);
        $locker = new Locker($redis);

        $key = 'key1';
        $userId = 123;

        $redis->method('get')->with($key)->willReturn((string)$userId);

        $this->assertSame($userId, $locker->getLockedBy($key));
    }

    public function testSetLock()
    {
        $redis = $this->createMock(Redis::class);
        $locker = new Locker($redis);

        $key = 'key2';
        $userId = 456;
        $ttl = 300;

        $redis->expects($this->once())->method('set')->with($key, $userId, $ttl);

        $locker->setLock($key, $userId, $ttl);
    }

    public function testRefreshLock()
    {
        $redis = $this->createMock(Redis::class);
        $locker = new Locker($redis);

        $key = 'key3';
        $userId = 789;
        $ttl = 300;

        $redis->method('get')->with($key)->willReturn((string)$userId);
        $redis->expects($this->once())->method('expire')->with($key, $ttl);

        $locker->refreshLock($key, $userId, $ttl);
    }

    public function testRefreshLockWhenUserNotMatch()
    {
        $redis = $this->createMock(Redis::class);
        $locker = new Locker($redis);

        $key = 'key4';
        $userId = 111;
        $anotherUserId = 222;
        $ttl = 300;

        $redis->method('get')->with($key)->willReturn((string)$userId);
        $redis->expects($this->never())->method('expire');

        $locker->refreshLock($key, $anotherUserId, $ttl);
    }
}
