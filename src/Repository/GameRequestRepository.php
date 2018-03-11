<?php

namespace App\Repository;

use App\Entity\GameRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GameRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameRequest[]    findAll()
 * @method GameRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GameRequest::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('g')
            ->where('g.something = :value')->setParameter('value', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
