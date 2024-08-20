<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;

class CommentManager
{
    public function create(BlogPost $post, User $user): Comment
    {
        $comment = new Comment();
        $comment->setBlogPost($post);
        $comment->setCreatedByUser($user);

        return $comment;
    }
}
