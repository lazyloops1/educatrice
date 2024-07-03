<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

   public function findByAvis($value): array
   {
       return $this->createQueryBuilder('a')
           ->andWhere('a.nom = :val')
           ->setParameter('val', $value)
           ->orderBy('a.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }
}
