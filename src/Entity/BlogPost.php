<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

final class BlogPost
{
    /**
     * @var ?string
     *
     * @ORM\Column(name="title", type="string")
     */
    private $title = null;

    /**
     * @var ?string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body = null;

    /**
     * @var bool
     *
     * @ORM\Column(name="draft", type="boolean")
     */
    private $draft = false;

    /**
     * @var Collection
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="blogPosts")
     */
    private $category = null;
}