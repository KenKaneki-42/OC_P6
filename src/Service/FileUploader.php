<?php

namespace App\Service;

use App\Entity\Trick;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\User;
use App\Entity\ProfilPicture;

class FileUploader
{
  private $targetDirectory;
  private $slugger;

  public function __construct(string $targetDirectory, SluggerInterface $slugger)
  {
    $this->targetDirectory = $targetDirectory;
    $this->slugger = $slugger;
  }

  public function upload(UploadedFile $file): string
  {
    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $safeFilename = $this->slugger->slug($originalFilename);
    $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

    try {
      $file->move($this->getTargetDirectory(), $fileName);
    } catch (FileException $e) {
      throw new FileException($e);
    }

    return $fileName;
  }

  public function uploadProfilPicture(ProfilPicture $profilPicture): void
  {
    if ($profilPicture->getFile() !== null) {
      $fileName = $this->upload($profilPicture->getFile()); // Use upload to handle file
      $profilPicture->setName($fileName);
      $profilPicture->setUrl($this->getTargetDirectory() . '/' . $fileName);
      $profilPicture->setDescription($fileName);
      $profilPicture->setSource('upload');
      $profilPicture->setExternalId('none');
    }
  }

  public function uploadFiles(Trick|User $entity): void
  {

    if ($entity instanceof Trick) {
      $files = $entity->getImages();
    } elseif ($entity instanceof User) {
      $files = $entity->getProfilPictures();
    } else {
      throw new \Exception('The entity must be an instance of Trick or User');
    }
    foreach ($files as $file) {

      if ($file->getFile() !== null) {
        $file->setName($this->upload($file->getFile()));
        $file->setUrl($this->getTargetDirectory() . '/' . $file->getName());
        $file->setDescription($entity->getName());
        $file->setSource('upload');
        $file->setExternalId('none');
      } elseif ($file->getName() === null && $file->getFile() === null) {
        $entity->removeFileMedia($file);
      }
    }
  }

  public function getTargetDirectory(): string
  {
    return $this->targetDirectory;
  }

  public function uploadVideos(Trick $trick): void
  {
    foreach ($trick->getVideos() as $video) {

      $check = parse_url($video->getUrl(), PHP_URL_HOST);
      parse_str(parse_url($video->getUrl(), PHP_URL_QUERY), $videoId);

      if ($check === "www.youtube.com" && array_key_exists('v', $videoId)) {
        $video->setexternalId($videoId['v']);
        $video->setSource($check);
        $video->setUrl($video->getUrl());
        $video->setName($trick->getName());
        $video->setDescription($trick->getName());
        $trick->addVideo($video);
      } elseif ($video->getName() === null || $video->getExternalId() === null) {
        $trick->removeVideo($video);
      }
    }
  }
}
