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
        subject: 'Activation de votre compte SnowTricks',
        htmlTemplate: 'confirmation_email',
        context: compact('user', 'token')
      );

      $message = sprintf("Votre compte a été crée, merci de le confirmer la création, un lien de validation vous a été envoyé par email à l'adresse suivante : %s", $user->getEmail());
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
      $this->addFlash('error', 'Token invalide.');
      return $this->redirectToRoute('app_register');
    }

    $user->setIsVerified(true);
    $user->setToken(null);
    $entityManager->persist($user);
    $entityManager->flush();

    $this->addFlash('success', 'Votre compte a été activé avec succès. Vous pouvez maintenant vous connecter.');

    return $this->redirectToRoute('app_login');
  }
}
