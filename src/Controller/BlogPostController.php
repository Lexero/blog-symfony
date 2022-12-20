<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

class BlogPostController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(BlogPostRepository $postRepository): Response
    {
        $blogPostRepository = $postRepository;
        $posts = $blogPostRepository->findAll();

        return $this->render('blog.html.twig', [
            'posts' => $posts,
            'controller_name' => 'BlogPostController',
        ]);
    }

    #[Route('/blog/{slug}', name: 'post_view')]
    public function post(#[MapEntity(mapping: ['slug' => 'slug'])] BlogPost $post): Response
    {
        return $this->render('post.html.twig', [
            'post' => $post
        ]);
    }
}
