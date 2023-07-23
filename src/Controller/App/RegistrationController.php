<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserCreatorService;
use App\Service\UserVerifierService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route(path: '/register', name: 'register')]
    public function register(
        Request             $request,
        UserCreatorService  $creatorService,
        UserVerifierService $verifierService
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $creatorService->registerUser($user, $form);
            $verifierService->sendVerificationEmailToUser($user);

            $this->addFlash('success-sent', 'Please, confirm your email!');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('login/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/verify/email/{confirmationCode}', name: 'verify_email')]
    public function verifyUserEmail(
        #[MapEntity(mapping: ["confirmationCode" => "confirmationCode", "verified" => false])] User $user,
        UserVerifierService                                                                         $verifierService
    ): Response
    {
        $verifierService->verifyUser($user);

        return $this->redirectToRoute('blog');
    }
}
