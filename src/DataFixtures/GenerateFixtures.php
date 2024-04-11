<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Comment;
use App\Entity\TrickCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\TokenGeneratorService;
use App\Service\SlugifyService;
use App\Entity\Image;
use App\Entity\Video;

class GenerateFixtures extends Fixture
{
  public function __construct(
    private UserPasswordHasherInterface $hasher,
    private TokenGeneratorService $tokenGenerator,
    private SlugifyService $slugger,
    private UserRepository $userRepository,
    private TrickRepository $trickRepository
  ) {
  }

  public function load(ObjectManager $manager): void
  {
    // Read data users from JSON file
    $userData = json_decode(file_get_contents(__DIR__ . '/usersData.json'), true);

    foreach ($userData as $userItem) {
      $user = $this->createUser(
        $userItem['email'],
        $userItem['password'],
        $userItem['firstName'],
        $userItem['lastName']
      );
      $manager->persist($user);
    }
    $manager->flush();

    $tricksCategoryData = json_decode(file_get_contents(__DIR__ . '/tricksCategoryData.json'), true);
    $tricksCategories = [];
    foreach ($tricksCategoryData as $trickCategoryItem) {
      $trickCategory = $this->createTrickCategory($trickCategoryItem['name']);
      $tricksCategories[] = $trickCategory;
      $manager->persist($trickCategory);
    }
    $manager->flush();

    $trickData = json_decode(file_get_contents(__DIR__ . '/tricksData.json'), true);

    foreach ($trickData as $trickItem) {
      $randomUser = $this->userRepository->findRandomUser();
      $randomCategory = $tricksCategories[array_rand($tricksCategories)];
      $image1 = $this->createImage($trickItem['image1']);
      $manager->persist($image1);
      $image2 = $this->createImage($trickItem['image2']);
      $manager->persist($image2);
      $video = $this->createVideo($trickItem['videoTutorial']);
      $manager->persist($video);
      $trick = $this->createTrick(
        $trickItem['name'],
        $randomCategory,
        $trickItem['description'],
        $image1,
        $image2,
        $video,
        $randomUser
      );
      $manager->persist($trick);
    }
    $manager->flush();

    $commentData = json_decode(file_get_contents(__DIR__ . '/commentsData.json'), true);
    foreach ($commentData as $commentItem) {
      $randomUser = $this->userRepository->findRandomUserDifferentFromAuthor($user);
      $randomTrick = $this->trickRepository->findRandomTrick();
      $comment = $this->createComment(
        $commentItem['content'],
        $randomUser,
        $randomTrick
      );

      $manager->persist($comment);
      $manager->flush();
    }
  }

  private function createUser(string $email, string $password, string $firstName, string $lastName): User
  {
    $user = new User();
    $user->setEmail($email)
      ->setPassword($this->hasher->hashPassword($user, $password))
      ->setRoles(['ROLE_USER'])
      ->setFirstname($firstName)
      ->setLastname($lastName)
      ->setToken($this->tokenGenerator->generateToken());

    return $user;
  }

  private function createTrick(string $name, TrickCategory $category, string $description, Image $image1, Image $image2, Video $video, User $user): Trick
  {
    $trick = new Trick();
    $trick->setName($name)
      ->setDescription($description)
      ->setTrickCategory($category)
      ->addImage($image1)
      ->addImage($image2)
      ->addVideo($video)
      ->setUser($user)
      ->setSlug($this->slugger->slugify($name));

    return $trick;
  }

  private function createComment(string $content, User $user, Trick $trick): Comment
  {
    $comment = new Comment();
    $comment->setUser($user)
      ->setContent($content)
      ->setTrick($trick);

    return $comment;
  }

  private function createTrickCategory(string $name): TrickCategory
  {
    $trickCategory = new TrickCategory();
    $trickCategory->setName($name);

    return $trickCategory;
  }

  private function createImage(string $url): Image
  {
    $image = new Image();
    $image->setName('Image name')
      ->setDescription('Image description')
      ->setExternalId('External ID')
      ->setSource('Source')
      ->setUrl($url);

    return $image;
  }

  private function createVideo(string $url): Video
  {
    $video = new Video();
    $video->setName('Video name')
      ->setDescription('Video description')
      ->setExternalId('External ID')
      ->setSource('Source')
      ->setUrl($url);

    return $video;
  }
}
