<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use App\Entity\Trick;
use App\Form\TrickFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Service\FileUploader;

use App\Repository\TrickRepository;

#[Route('/trick')]
class TrickController extends AbstractController
{
  #[Route('/', name: 'app_trick_index')]
  public function index(TrickRepository $trickRepository): Response
  {

    return $this->render('trick/index.html.twig', [
      'controller_name' => 'TrickController',
      'tricks' => $trickRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'app_trick_new', methods: ['GET', 'POST'])]
  public function new(Request $request, TrickRepository $trickRepository, FileUploader $fileUploader): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick, ['validation_groups' => 'new']);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $slugger = new AsciiSlugger();
            $slug = $slugger->slug($form->getData()->getName());
            $trick->setSlug(strtolower($slug));
            $fileUploader->uploadImages($trick);
            $fileUploader->uploadVideos($trick);
            $trickRepository->add($trick, true);

            $this->addFlash('success', 'La figure a bien été créée');

            return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

  #[Route('/{slug}', name: 'app_trick_show', requirements: ['slug' => Requirement::ASCII_SLUG], methods: ['GET'])]
  public function show(Trick $trick): Response
  {
    return $this->render('trick/show.html.twig', compact('trick'));
  }

  #[Route('/{slug}/edit', name: 'app_trick_edit', requirements: ['slug' => Requirement::ASCII_SLUG], methods: ['GET', 'POST'])]
  public function edit(Request $request, Trick $trick, TrickRepository $trickRepository, FileUploader $fileUploader, EntityManagerInterface $em): Response
  {
    return $this->render('trick/edit.html.twig', compact('trick'));
  }

  #[Route('/{slug}/delete', name: 'app_trick_delete', requirements: ['slug' => Requirement::ASCII_SLUG], methods: ['POST'])]
  public function delete(Trick $trick): Response
  {
    return $this->redirectToRoute('app_trick');
  }
}
