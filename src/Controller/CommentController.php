<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Form\CommentFormType;

class CommentController extends AbstractController
{
  #[Route('/comment', name: 'app_comment')]
  public function index(): Response
  {
    return $this->render('comment/index.html.twig', [
      'controller_name' => 'CommentController',
    ]);
  }

  #[Route('/comment/new', name: 'app_comment_new')]
  public function new(Request $request, CommentRepository $commentRepository): Response
  {
    $comment = new Comment();
    $form = $this->createForm(CommentFormType::class, $comment, ['validation_groups' => 'new']);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $commentRepository->add($comment, true);
      $this->addFlash('success', 'Le commentaire a bien été créé');
      return $this->redirectToRoute('app_comment', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('comment/new.html.twig', [
      'controller_name' => 'CommentController',
    ]);
  }
}
