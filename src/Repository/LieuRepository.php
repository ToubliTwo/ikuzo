<?php

namespace App\Repository;

use App\Entity\Lieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Lieu>
 *
 * @method Lieu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lieu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lieu[]    findAll()
 * @method Lieu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LieuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieu::class);
    }

    public function findByVilleQueryBuilder(int $villeId): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('l')
            ->join('l.ville', 'v')
            ->andWhere('v.id = :villeId')
            ->setParameter('villeId', $villeId) // Utiliser l'ID de la ville
            ->orderBy('l.nom', 'ASC');
    }
/*    public function findByVille(int $villeId): array
    {
        return $this->createQueryBuilder('l')
            ->join('l.ville', 'v')
            ->andWhere('v.id = :villeId')
            ->setParameter('villeId', $villeId)
            ->orderBy('l.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }*/
}
