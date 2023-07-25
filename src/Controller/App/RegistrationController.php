<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Entity\User;
use App\Form\RegistrationQueryType;
use App\Service\UserCreator;
use App\Service\UserVerifier;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route(path: '/registration', name: 'registration')]
    public function registration(
        Request             $request,
        UserCreator         $creatorService,
        UserVerifier $verifierService
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationQueryType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $creatorService->registrationUser($user, $form);
            $verifierService->sendVerificationEmailToUser($user);

            $this->addFlash('success-sent', 'Please, confirm your email!');

            return $this->redirectToRoute('login');
        }

        return $this->render('login/registration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/verify/email/{confirmationCode}', name: 'verify_email')]
    public function verifyUserEmail(
        #[MapEntity(mapping: ["confirmationCode" => "confirmationCode", "verified" => false])] User $user,
        UserVerifier $verifierService
    ): Response
    {
        $verifierService->verifyUser($user);

        return $this->redirectToRoute('main');
    }
}
