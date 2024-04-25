<?php

namespace App\Repository;

use App\Entity\Sorties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;


/**
 * @extends ServiceEntityRepository<Sorties>
 *
 * @method Sorties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sorties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sorties[]    findAll()
 * @method Sorties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortiesRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sorties::class);
        $this->security = $security;
    }
    public function findByCampus($campusId)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.campus = :campusId')
            ->setParameter('campusId', $campusId)
            ->getQuery()
            ->getResult();
    }
    public function findByCriteria($campusId, $organisateur, $inscrit, $pasInscrit, $sortiesPassees)
    {
        $user = $this->security->getUser();

        $queryBuilder = $this->createQueryBuilder('s')
            ->leftJoin('s.campus', 'c');

        if ($campusId) {
            $queryBuilder
                ->andWhere('c.id = :campusId')
                ->setParameter('campusId', $campusId);
        }

        if ($organisateur) {
            // Ajouter le filtre pour les sorties dont l'utilisateur est l'organisateur
            $queryBuilder
                ->andWhere('s.organisateur = :userId')
                ->setParameter('userId', $user->getId()); // Remplacer '1' par l'ID de l'utilisateur actuel
        }

        if ($inscrit) {
            // Ajouter le filtre pour les sorties auxquelles l'utilisateur est inscrit
            $queryBuilder
                ->andWhere(':userId MEMBER OF s.users')
                ->setParameter('userId', $user->getId()); // Remplacer '1' par l'ID de l'utilisateur actuel
        }

        if ($pasInscrit) {
            // Ajouter le filtre pour les sorties auxquelles l'utilisateur n'est pas inscrit
            $queryBuilder
                ->andWhere(':userId NOT MEMBER OF s.users')
                ->setParameter('userId', $user->getId()); // Remplacer '1' par l'ID de l'utilisateur actuel
        }

        if ($sortiesPassees) {
            // Ajouter le filtre pour les sorties passées
            $queryBuilder
            ->andWhere('s.date < :currentDate')
            ->setParameter('currentDate', new \DateTime());
        }

        // Exécuter la requête
        return $queryBuilder->getQuery()->getResult();
    }

}
