<?php

namespace App\Service;

use App\Entity\Trick;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

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

  public function uploadImages(Trick $trick): void
  {
    foreach ($trick->getImages() as $image) {
      if ($image->getFile() !== null) {
        $image->setName($this->upload($image->getFile()));
        $image->setUrl($this->getTargetDirectory() . '/' . $image->getName());
        $image->setDescription($image->getDescription());
        $image->setSource('upload');
        $image->setExternalId('none');
      } elseif ($image->getName() === null && $image->getFile() === null) {
        $trick->removeImage($image);
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
        $video->setName($video->getName());
        $video->setDescription($video->getDescription());
        $trick->addVideo($video);
      } elseif ($video->getName() === null || $video->getExternalId() === null) {
        $trick->removeVideo($video);
      }
    }
  }
}
