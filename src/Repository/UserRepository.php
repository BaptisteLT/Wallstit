<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    
    /**
     * L'identifiant de l'utilisateur est custom car il contient le nom du provider + l'id du compte du provider, ils sont tous les deux séparés par @@@
     *
     * @param string $userIdentifier ex: google@@@4v6qb4c4fbzebxdw4ippmpg5jq6ho4
     * @return User|null
     */
    public function loadUserByIdentifier(string $userIdentifier): ?User
    {
        [0 => $providerName, 1 => $providerAccountId] = explode("@@@", $userIdentifier);

        return $this->createQueryBuilder('u')
            ->where('u.OAuth2Provider = :providerName')
            ->andWhere('u.OAuth2ProviderId = :providerAccountId')
            ->setParameters([
                'providerName' => $providerName,
                'providerAccountId' => $providerAccountId
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
