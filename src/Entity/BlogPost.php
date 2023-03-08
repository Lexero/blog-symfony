<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BlogPostRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: BlogPostRepository::class)]
#[ORM\HasLifecycleCallbacks]
class BlogPost
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(name: "created_at")]
    protected DateTime $createdAt;

    #[ORM\Column(name: "updated_at")]
    protected DateTime $updatedAt;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    private User|null $author;

    public function __construct(User $author)
    {
        $this->createdAt = new DateTime('now');
        $this->author = $author;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    #[ORM\PrePersist]
    public function refreshUpdatedAt(): void
    {
        $this->updatedAt = new DateTime('now');
    }

    public function getAuthor(): User
    {
        return $this->author;
    }
}
