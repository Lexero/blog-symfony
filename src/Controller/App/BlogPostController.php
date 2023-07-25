<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogPostController extends AbstractController
{
    #[Route(path: '/blog', name: 'blog')]
    public function blog(BlogPostRepository $postRepository): Response
    {
        $posts = $postRepository->getLatestPosts(15);

        return $this->render('blog/main.html.twig', [
            'posts' => $posts,
            'controller_name' => 'BlogPostController',
        ]);
    }

    #[Route(path: '/blog/{slug}', name: 'post_view')]
    public function post(#[MapEntity(mapping: ['slug' => 'slug'])] BlogPost $post): Response
    {
        return $this->render('blog/post.html.twig', [
            'post' => $post
        ]);
    }
}
