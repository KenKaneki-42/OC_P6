<?php

namespace App\Service;

class TokenGenerator
{
  public function generateToken(): string
  {
    $randomBytes = bin2hex(random_bytes(32));
    $hashedToken = hash('sha256', $randomBytes);
    return $hashedToken;
  }
}
