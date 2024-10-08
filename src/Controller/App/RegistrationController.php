<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Entity\User;
use App\Event\RegisteredUserEvent;
use App\Form\RegistrationType;
use App\Service\UserCreator;
use App\Service\UserVerifier;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route(path: '/registration', name: 'app_registration', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function registration(
        Request                  $request,
        UserCreator              $creatorService,
        EventDispatcherInterface $eventDispatcher
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $creatorService->registrationUser($user, $form);

            $userRegisteredEvent = new RegisteredUserEvent($user);
            $eventDispatcher->dispatch($userRegisteredEvent, RegisteredUserEvent::NAME);

            $this->addFlash('success-sent', 'Please, confirm your email!');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('login/registration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/verify/email/{confirmationCode}', name: 'app_verify_email', methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function verifyUserEmail(
        #[MapEntity(mapping: ["confirmationCode" => "confirmationCode", "isVerified" => false])]
        User         $user,
        UserVerifier $verifierService
    ): Response
    {
        $verifierService->verifyUser($user);

        return $this->redirectToRoute('app_main');
    }
}
