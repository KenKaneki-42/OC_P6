<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Trick;

#[Route('/', name: 'app_')]
class HomeController extends AbstractController
{
  public function __construct(private EntityManagerInterface $em)
  {
    $this->em = $em;
  }
  #[Route('{offset<\d+>?0}', name: 'index', methods: ['GET'])]
  public function index(Request $request): Response
  {
    $limit = 10;
    $offset = (int) $request->query->get('offset', 0);

    $tricks = $this->em->getRepository(Trick::class)->findBy([], null, $limit, $offset);
    $totalTricks = $this->em->getRepository(Trick::class)->count([]);
    $hasMore = ($offset + $limit) < $totalTricks;
    // dump($hasMore);
    // dump('offset: ', $offset);
    // dump('limit: ', $limit);

    if ($request->isXmlHttpRequest()) {
      $html = $this->renderView('trick/_list.html.twig', [
        'tricks' => $tricks
      ]);
      return new JsonResponse(['html' => $html, 'hasMore' => $hasMore]);
    } else {
      error_log('Not an AJAX request');

    }

    return $this->render('home/index.html.twig', [
      'tricks' => $tricks,
      'hasMore' => $hasMore,
      'TricksLength' => count($tricks),
      'totalTricks' => $totalTricks,
    ]);

  }
}
