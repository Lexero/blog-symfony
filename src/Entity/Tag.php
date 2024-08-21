<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PostTagRepository;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Ramsey\Uuid\Uuid;
use DateTimeImmutable;

#[Entity(repositoryClass: PostTagRepository::class)]
#[Table(name: 'tags', indexes: [
    new Index(columns: ['name']),
    new Index(columns: ['created_at'])
])]
class Tag
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[Column]
    private string $name;

    #[Column]
    private DateTimeImmutable $createdAt;

    public function __construct(string $name)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->name = $name;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
