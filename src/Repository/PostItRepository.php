<?php

namespace App\Repository;

use App\Entity\PostIt;
use Symfony\Component\Uid\Uuid;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<PostIt>
 *
 * @method PostIt|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostIt|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostIt[]    findAll()
 * @method PostIt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostItRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostIt::class);
    }

    public function findOneByUserAndUuid($user, $uuid): ?PostIt
    {
        /*dump($this->createQueryBuilder('p')
        ->join('p.wall', 'pw')
        ->andWhere('pw.user = :user')
        ->andWhere('p.uuid = :uuid')
        ->setParameters(['user' => $user, 'uuid' => Uuid::fromString($uuid)->toBinary()])
        ->getQuery()
        ->getSQL());die;*/

        return $this->createQueryBuilder('p')
            ->join('p.wall', 'pw')
            ->andWhere('pw.user = :user')
            ->andWhere('p.uuid = :uuid')
            ->setParameters(['user' => $user, 'uuid' => Uuid::fromString($uuid)->toBinary()])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return PostIt[] Returns an array of PostIt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PostIt
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
