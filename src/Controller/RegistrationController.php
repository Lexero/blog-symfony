<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserCreatorService;
use App\Form\RegistrationFormType;
use App\Service\UserVerifierService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
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

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email/{confirmationCode}', name: 'app_verify_email')]
    public function verifyUserEmail(
        #[MapEntity(mapping: ["confirmationCode" => "confirmationCode", "verified" => false])] User $user,
        UserVerifierService                                                                         $verifierService
    ): Response
    {
        $verifierService->verifyUser($user);

        return $this->redirectToRoute('blog');
    }
}
