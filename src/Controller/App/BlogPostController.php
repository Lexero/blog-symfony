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
    #[Route(path: '/posts', name: 'main', methods: "GET")]
    public function blog(BlogPostRepository $postRepository): Response
    {
        $posts = $postRepository->getLatestPosts(15);

        return $this->render('blog/main.html.twig', [
            'posts' => $posts,
            'controller_name' => 'BlogPostController',
        ]);
    }

    #[Route(path: '/posts/search', name: 'post_search', methods: "GET")]
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

    #[Route(path: '/posts/{slug}', name: 'post_view', methods: "GET")]
    public function post(#[MapEntity(mapping: ['slug' => 'slug'])] BlogPost $post): Response
    {
        return $this->render('blog/post.html.twig', [
            'post' => $post
        ]);
    }
}
