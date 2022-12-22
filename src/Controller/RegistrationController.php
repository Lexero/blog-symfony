<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\UserRoleTypeEnum;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request,
                             UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface $entityManager,
                             MailerInterface $mailer): Response
    {
//        dump($request->request->all());
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles([UserRoleTypeEnum::ROLE_READER]);
            $user->setConfirmationCode(Uuid::uuid6()->toString());
            $entityManager->persist($user);
            $entityManager->flush();

            $email = new TemplatedEmail();
            $email->from(new Address('mailer@your.com', 'Blog Admin'));
            $email->to($user->getEmail());
            $email->htmlTemplate('registration/confirmation_email.html.twig');
            $email->subject("Hello! Please verify your email");
            $email->context(['signedUrl' => $this->generateUrl("app_verify_email", ["confirmationCode" => $user->getConfirmationCode()], UrlGeneratorInterface::ABSOLUTE_URL)]);

            $mailer->send($email);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email/{confirmationCode}', name: 'app_verify_email')]
    public function verifyUserEmail(UserRepository $userRepository,
                                    EntityManagerInterface $entityManager,
                                    string $confirmationCode): Response
    {

        $user = $userRepository->findOneBy(["confirmationCode" => $confirmationCode, "verified" => false]);
        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }
        // validate email confirmation link, sets User::isVerified=true and persists
        $user->setVerified(true);
        $user->setEnable(true);
        $entityManager->persist($user);
        $entityManager->flush();
//        TODO auth after verification
        $this->addFlash("success", "your email is verified");

        return $this->redirectToRoute('blog');
    }
}
