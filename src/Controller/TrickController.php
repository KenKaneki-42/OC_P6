<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use App\Entity\Trick;
use App\Form\TrickFormType;
use Symfony\Component\String\Slugger\AsciiSlugger;
use App\Service\FileUploader;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Form\CommentFormType;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\TrickRepository;


#[Route('/trick')]
class TrickController extends AbstractController
{
  #[Route('', name: 'app_trick_index')]
  public function index(TrickRepository $trickRepository): Response
  {

    return $this->render('trick/index.html.twig', [
      'controller_name' => 'TrickController',
      'tricks' => $trickRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'app_trick_new', methods: ['GET', 'POST'])]
  #[IsGranted('IS_AUTHENTICATED')]
  public function new(Request $request, TrickRepository $trickRepository, FileUploader $fileUploader): Response
  {

    $trick = new Trick();
    $form = $this->createForm(TrickFormType::class, $trick, ['validation_groups' => 'new']);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $slugger = new AsciiSlugger();
      $slug = $slugger->slug($form->getData()->getName());
      $trick->setSlug(strtolower($slug));
      $trick->setUser($this->getUser());
      $trick->setDescription($form->getData()->getDescription());
      $fileUploader->uploadFiles($trick);
      $fileUploader->uploadVideos($trick);
      $trick->setTrickCategory($form->getData()->getTrickCategory());
      $trickRepository->add($trick, true);

      $this->addFlash('success', 'La figure a bien été créée');

      return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('trick/new.html.twig', [
      'trick' => $trick,
      'form' => $form,
    ]);
  }

  #[Route('/show/{slug}', name: 'app_trick_show', requirements: ['slug' => Requirement::ASCII_SLUG], methods: ['GET', 'POST'])]
  public function show(Request $request, Trick $trick, CommentRepository $commentRepository, Security $security): Response
  {
    $limit = 5;
    $offset = (int) $request->query->get('offset', 0);
    
    $comment = new Comment();
    $form = $this->createForm(CommentFormType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user = $security->getUser();
      $comment->setTrick($trick);
      $comment->setUser($user);
      $commentRepository->add($comment, true);

      return $this->redirectToRoute('app_trick_show', ['slug' => $trick->getSlug()]);
    }

    $comments = $commentRepository->findBy(['trick' => $trick], null, $limit, $offset);
    $totalComments = $commentRepository->count(['trick' => $trick]);
    $hasMore = ($offset + $limit) < $totalComments;

    if ($request->isXmlHttpRequest()) {
      $html = $this->renderView('comment/_list.html.twig', [
        'comments' => $comments
      ]);
      return new JsonResponse(['html' => $html, 'hasMore' => $hasMore]);
    } else {
      error_log('Not an AJAX request');
    }

    return $this->render('trick/show.html.twig', [
      'trick' => $trick,
      // here we passe a pack of comments ( we avoid to use trick.comments because we don't want to display all of them at first)
      'comments' => $comments,
      'hasMore' => $hasMore,
      'form' => $form->createView(),
      'totalComments' => $totalComments,
    ]);
  }

  // #[Route('/trick/{slug}/comments/{offset<\d+>?0}', name: 'app_trick_comments_show_list', methods: ['GET'])]
  // public function addListComments(Trick $trick, CommentRepository $commentRepository, Request $request)
  // {
  //   $offset = $request->query->getInt('offset', 0);
  //   $limit = 10; // Nombre de commentaires à afficher par page

  //   $comments = $commentRepository->findByTrick($trick, $limit, $offset);
  //   $totalComments = $commentRepository->countByTrick($trick);

  //   $hasMore = ($offset + $limit) < $totalComments;

  //   return $this->render('trick/comments.html.twig', [
  //     'trick' => $trick,
  //     'comments' => $comments,
  //     'hasMore' => $hasMore,
  //   ]);
  // }

  #[Route('/edit/{slug}', name: 'app_trick_edit', requirements: ['slug' => Requirement::ASCII_SLUG], methods: ['GET', 'POST'])]
  #[IsGranted('IS_AUTHENTICATED')]
  #[IsGranted('', subject: 'trick')]
  public function edit(Request $request, Trick $trick, TrickRepository $trickRepository, FileUploader $fileUploader): Response
  {

    $trick = $trickRepository->find($trick->getId());
    $form = $this->createForm(TrickFormType::class, $trick, ['validation_groups' => 'edit']);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $slugger = new AsciiSlugger();
      $slug = $slugger->slug($form->getData()->getName());
      $trick->setSlug(strtolower($slug));
      $trick->setUser($this->getUser());
      $trick->setDescription($form->getData()->getDescription());
      $fileUploader->uploadFiles($trick);
      $fileUploader->uploadVideos($trick);
      $trick->setTrickCategory($form->getData()->getTrickCategory());
      $trickRepository->add($trick, true);

      $this->addFlash('success', 'La figure a bien été modifiée');

      return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
    }
    return $this->render('trick/edit.html.twig', [
      'trick' => $trick,
      'form' => $form,
    ]);
  }

  #[Route('/delete/{id}', name: 'app_trick_delete', methods: ['GET', 'POST'])]
  #[IsGranted('IS_AUTHENTICATED')]
  #[IsGranted('', subject: 'trick')]
  public function delete(Request $request, Trick $trick, TrickRepository $trickRepository): Response
  {
    $token = $request->request->get('_token');

    if ($this->isCsrfTokenValid(sprintf('delete%s', $trick->getId()), $token)) {
      $trickRepository->remove($trick, true);
    } else {
      $this->addFlash('danger', 'La figure n\'a pas pu être supprimée');
    }

    return $this->redirectToRoute('app_trick_index', [], Response::HTTP_SEE_OTHER);
  }
}
