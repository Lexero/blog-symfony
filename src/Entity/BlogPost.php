<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BlogPostRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: BlogPostRepository::class)]
#[ORM\Table(name: 'posts')]
#[ORM\Index(columns: ['created_at'])]
#[ORM\HasLifecycleCallbacks]
class BlogPost
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: 'datetimetz_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetimetz_immutable')]
    private DateTimeImmutable $updatedAt;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'author_id', referencedColumnName: 'id', onDelete: "SET NULL")]
    private User $author;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Tag::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: 'post_tags')]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: false)]
    private Collection $categories;

    public function __construct(User $user)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = $this->createdAt;
        $this->author = $user;
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): string
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

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function updateUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }

    public function addComment(Comment $comment): void
    {
        $comment->setBlogPost($this);

        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }
    }

    public function removeComment(Comment $comment): void
    {
        $this->comments->removeElement($comment);
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }
}
