<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    public function findRandomUser(): ?User
    {
      $users = $this->findAll();

      if (count($users) === 0) {
          return null;
      }

      $randomUser = $users[array_rand($users)];

      return $randomUser;
  }
    public function findRandomUserDifferentFromAuthor(User $author): ?User
    {
        $users = $this->findAll();

        // Filter the array to exclude the author
        $otherUsers = array_filter($users, function($user) use ($author) {
            return $user->getId() !== $author->getId();
        });

        if (count($otherUsers) === 0) {
            // Return null if no other users are found
            return null;
        }

        // Re-index the keys of the array after filtering
        $otherUsers = array_values($otherUsers);

        // Select a random user from the other users
        $randomUser = $otherUsers[array_rand($otherUsers)];

        return $randomUser;
    }

}
