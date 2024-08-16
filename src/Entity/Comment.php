<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table]
#[ORM\HasLifecycleCallbacks]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'text', nullable: false)]
    private string $comment;

    #[ORM\ManyToOne(targetEntity: BlogPost::class, inversedBy: "comments")]
    #[ORM\JoinColumn(referencedColumnName: "id")]
    private BlogPost $post;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "comments")]
    #[ORM\JoinColumn(referencedColumnName: "id")]
    private User $createdByUser;

    private DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    private function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getCreatedByUser(): User
    {
        return $this->createdByUser;
    }

    public function getBlogPost(): BlogPost
    {
        return $this->post;
    }

    public function setBlogPost(BlogPost $post): void
    {
        $this->post = $post;
    }

    public function setCreatedByUser(User $user): void
    {
        $this->createdByUser = $user;
    }

    #[ORM\PreUpdate]
    public function refreshUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable('now');
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}
