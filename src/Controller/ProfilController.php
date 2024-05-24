<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProfilFormType;
use App\Service\FileUploader;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ProfilPicture;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/profil')]
#[IsGranted('IS_AUTHENTICATED')]
class ProfilController extends AbstractController
{
  #[Route('/', name: 'app_profil')]
  public function index(#[CurrentUser] ?User $user): Response
  {
    $user = $this->getUser();
    return $this->render('profil/index.html.twig', [
      'user' => $user,
    ]);
  }

  #[Route('/edit', name: 'app_profil_update', methods: ['GET', 'POST'])]
  public function update(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
  {
    $user = $this->getUser();
    $form = $this->createForm(ProfilFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $file = $form->get('profilPictures')->getData();
      if ($file) {
        $profilPicture = new ProfilPicture();
        $profilPicture->setUser($user);
        $profilPicture->setFile($file);
        $fileUploader->uploadProfilPicture($profilPicture);
        $em->persist($profilPicture);
      }
      $em->flush();
      $this->addFlash('success', 'Votre profil a bien été mis à jour');
      return $this->redirectToRoute('app_profil');
    }

    return $this->render('profil/edit.html.twig', [
      'form' => $form->createView(),
      'user' => $user,
    ]);
  }
}
