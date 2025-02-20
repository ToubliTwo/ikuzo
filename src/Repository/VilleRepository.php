<?php

namespace App\Repository;

use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ville>
 *
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }


    public function fingAllOrderedByNameQueryBuilder() : \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('v')->orderBy('v.nom', 'ASC');
    }
  
    public function findByCriteriaWithVille(
        $nomVille = null
    ) {

            $queryBuilder= $this->createQueryBuilder('v')
                ->andWhere('v.nom LIKE :nomVille')
                ->setParameter('nomVille', '%' . $nomVille . '%');


        // Exécuter la requête
        return $queryBuilder->getQuery()->getResult();
    }
}
