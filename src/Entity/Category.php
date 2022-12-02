<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class Category
{
    /**
     * @var ?string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name = null;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="BlogPost", mappedBy="category")
     */
    private $blogPosts;

    public function __construct()
    {
        $this->blogPosts = new ArrayCollection();
    }

    public function getBlogPosts(): Collection
    {
        return $this->blogPosts;
    }
}