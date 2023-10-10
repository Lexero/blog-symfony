<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogPostController extends AbstractController
{
    #[Route(path: '/posts', name: 'app_main', methods: Request::METHOD_GET)]
    public function blog(BlogPostRepository $postRepository): Response
    {
        $posts = $postRepository->getLatestPosts(15);

        return $this->render('blog/main.html.twig', [
            'posts' => $posts,
            'controller_name' => 'BlogPostController',
        ]);
    }

    #[Route(path: '/posts/search', name: 'app_post_search', methods: Request::METHOD_GET)]
    public function search(Request $request, BlogPostRepository $blogPostRepository): Response
    {
        $query = $request->query->get('title');
        if ($query) {
            $posts = $blogPostRepository->searchByTitle($query);
        } else {
            $posts = [];
        }

        return $this->render('blog/search.html.twig', [
                'posts' => $posts
            ]
        );
    }

    #[Route(path: '/posts/{slug}', name: 'app_post_view', methods: Request::METHOD_GET)]
    public function post(#[MapEntity(mapping: ['slug' => 'slug'])] BlogPost $post): Response
    {
        return $this->render('blog/post.html.twig', [
            'post' => $post
        ]);
    }
}
