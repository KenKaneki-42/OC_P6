<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends AbstractController
{
  #[Route(path: '/login', name: 'app_login')]
  public function login(AuthenticationUtils $authenticationUtils): Response
  {
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();

    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('security/login.html.twig', [
      'last_username' => $lastUsername,
      'error' => $error,
    ]);
  }

  #[Route(path: '/logout', name: 'app_logout')]
  public function logout(): void
  {
    throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall.');
  }
  
  #[Route(path: '/forgotten-password', name: 'app_forgotten_password')]
  public function forgottenPassword(
    Request $request,
    UserRepository $userRepository,
    TokenGeneratorInterface $tokenGenerator,
    EntityManagerInterface $entityManager,
    SendMailService $mail,
  ): Response {
    $form = $this->createForm(ResetPasswordRequestFormType::class);
    $form->handleRequest($request);

    if (!$form->isSubmitted() || !$form->isValid()) {
      return $this->render('security/reset_password_request.html.twig', [
        'requestPassForm' => $form->createView(),
      ]);
    }

    $user = $userRepository->findOneByEmail($form->get('email')->getData());
    if (!$user) {
      $this->addFlash('danger', 'A problem occurred, please try again later.');
      return $this->redirectToRoute('app_login');
    }

    // generate a reset token
    $token = $tokenGenerator->generateToken();
    $user->setResetToken($token);
    $entityManager->persist($user);
    $entityManager->flush();

    // generate a URL for the password reset
    $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

    //send the email
    $mail->send(
      from: 'sylvain.vandermeersch@gmail.com',
      to: $user->getEmail(),
      subject: 'Reset your password',
      htmlTemplate: 'reset_password_email',
      context: compact('url', 'user')
    );

    $this->addFlash('success', 'An email has been sent to you with a link to reset your password.');
    return $this->redirectToRoute('app_login');
  }

  #[Route(path: '/reset-password/{token}', name: 'app_reset_password')]
  public function resetPassword(
    string $token,
    Request $request,
    UserRepository $userRepository,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher,
  ): Response {
    // check if the token is valid
    $user = $userRepository->findOneByResetToken($token);
    if (!$user) {
      $this->addFlash('danger', 'Invalid token.');
      return $this->redirectToRoute('app_login');
    }

    $form = $this->createForm(ResetPasswordFormType::class);
    $form->handleRequest($request);

    if (!$form->isSubmitted() || !$form->isValid()) {
      // display the form
      return $this->render('security/reset_password.html.twig', [
        'passForm' => $form->createView(),
      ]);
    }

    // update the password
    $user->setResetToken(null);
    $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));
    $entityManager->persist($user);
    $entityManager->flush();

    $this->addFlash('success', 'Password updated successfully.');
    return $this->redirectToRoute('app_login');
  }
}
