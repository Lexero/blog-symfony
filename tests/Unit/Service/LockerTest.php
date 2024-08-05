<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\Locker;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Redis;

class LockerTest extends WebTestCase
{
    /** @throws Exception */
    public function testGetLockedBy()
    {
        $redis = $this->createMock(Redis::class);
        $locker = new Locker($redis);

        $key = 'key1';
        $userId = 123;

        $redis->method('get')->with($key)->willReturn((string)$userId);

        $this->assertSame($userId, $locker->getLockedBy($key));
    }

    /** @throws Exception */
    public function testSetLock()
    {
        $redis = $this->createMock(Redis::class);
        $locker = new Locker($redis);

        $key = 'key2';
        $userId = 456;

        $redis->expects($this->once())->method('set')->with($key, $userId);

        $locker->setLock($key, $userId);
    }

    /** @throws Exception */
    public function testRefreshLock()
    {
        $redis = $this->createMock(Redis::class);
        $locker = new Locker($redis);

        $key = 'key3';
        $userId = 789;

        $redis->method('get')->with($key)->willReturn((string)$userId);
        $redis->expects($this->once())->method('expire')->with($key);

        $locker->refreshLock($key, $userId);
    }

    /** @throws Exception */
    public function testRefreshLockWhenUserNotMatch()
    {
        $redis = $this->createMock(Redis::class);
        $locker = new Locker($redis);

        $key = 'key4';
        $userId = 111;
        $anotherUserId = 222;

        $redis->method('get')->with($key)->willReturn((string)$userId);
        $redis->expects($this->never())->method('expire');

        $locker->refreshLock($key, $anotherUserId);
    }
}
