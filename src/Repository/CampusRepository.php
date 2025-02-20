<?php

namespace App\Repository;

use App\Entity\Campus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Campus>
 *
 * @method Campus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campus[]    findAll()
 * @method Campus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campus::class);
    }

        /**
         * @return Campus[] Returns an array of Campus objects
         */
        public function findByCriteriaWithCampus ($nom=null )
        {
            $queryBuilder = $this->createQueryBuilder('c')
            ->andWhere('c.nom like :nomCampus')
            ->setParameter('nomCampus', '%'.$nom.'%');

            return $queryBuilder -> getQuery()->getResult();
        }
}
