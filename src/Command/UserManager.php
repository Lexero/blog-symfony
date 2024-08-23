<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserManager
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /** @throws Exception */
    public function recordEvent(string $username, string $data): void
    {
        $sql = '
            INSERT INTO
                events (username, data, is_read)
            VALUES
                (:username, :data, 0)
        ';

        $this->em->getConnection()
            ->prepare($sql)
            ->executeQuery([
                'username' => $username,
                'data' => $data,
            ]);
    }
}
