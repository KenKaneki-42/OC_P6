<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{

  #[Route('/register', name: 'app_register')]
  public function register(
    Request $request,
    UserPasswordHasherInterface $userPasswordHasher,
    Security $security,
    EntityManagerInterface $entityManager,
    SendMailService $mail
  ): Response {
    $user = new User();
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      // encode the plainPassword
      $plainPassword = $form->get('plainPassword')->get('first')->getData();
      $user->setPassword(
        $userPasswordHasher->hashPassword(
          $user,
          $plainPassword
        )
      );
      $entityManager->persist($user);
      $entityManager->flush();

      $token = $user->getToken();

      $mail->send(
        from: 'sylvain.vandermeersch@gmail.com',
        to: $user->getEmail(),
        subject: 'Activate your account on SnowTricks',
        htmlTemplate: 'confirmation_email',
        context: compact('user', 'token')
      );

      $message = sprintf('Your account has been created, please confirm your account, a validation link has been sent on your email : %s', $user->getEmail());
      $this->addFlash(
        'notice',
        $message
      );

      return $security->login($user, 'form_login', 'main');
    }

    return $this->render('registration/register.html.twig', [
      'registrationForm' => $form,
    ]);
  }

  #[Route('/verify/email/{token}', name: 'app_verify_email')]
  public function verifyUserEmail(
    EntityManagerInterface $entityManager,
    string $token
  ): Response {
    $user = $entityManager->getRepository(User::class)->findOneBy(['token' => $token]);

    if (!$user) {
      $this->addFlash('error', 'Invalid token');
      return $this->redirectToRoute('app_register');
    }

    $user->setIsVerified(true);
    $user->setToken(null);
    $entityManager->persist($user);
    $entityManager->flush();

    $this->addFlash('success', 'Your email address has been verified.');

    return $this->redirectToRoute('app_login');
  }
}
