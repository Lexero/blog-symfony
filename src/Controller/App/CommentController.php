<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Form\CommentType;
use App\Manager\CommentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CommentController extends AbstractController
{
    #[Route(path: '/posts/{slug}', name: 'comment_new', methods: Request::METHOD_POST)]
    public function addComment(
        BlogPost               $post,
        Request                $request,
        CommentManager         $commentManager,
        EntityManagerInterface $em
    ): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('User is not authenticated or not of type User.');
        }

        $comment = $commentManager->create($post, $user);
        $post->addComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_post_view', ['slug' => $post->getSlug()]);
        }

        return $this->render('blog/post.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}
