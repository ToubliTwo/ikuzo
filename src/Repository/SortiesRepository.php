<?php

namespace App\Repository;

use App\Entity\Etat;
use App\Entity\Sorties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    private Security $security;
    public function __construct(ManagerRegistry $registry, Security $security, private readonly PaginatorInterface $paginator)
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
    public function findByCriteria($campusId,
                                   $organisateur,
                                   $inscrit,
                                   $pasInscrit,
                                   $sortiesPassees,
                                   $titre = null,
                                   $dateRechercheDebut = null,
                                   $dateRechercheFin = null,
                                  )
    {
        $user = $this->security->getUser();

        $queryBuilder = $this->createQueryBuilder('s')
            ->leftJoin('s.campus', 'c')
            ->leftJoin('s.etat', 'e')
            ->leftJoin('s.users', 'u')
            ->leftJoin('s.lieu', 'l')
            ->addSelect('u')
            ->addSelect('c')
            ->addSelect('e')
            ->where('e.libelle NOT IN (:etatsExclus)')
            ->setParameter('etatsExclus', ['Archivée', 'Annulée']);;

        if ($titre) {
            // Ajouter le filtre pour les sorties dont le titre contient le mot-clé
            $queryBuilder
                ->andWhere('s.titre LIKE :titre')
                ->setParameter('titre', '%' . $titre . '%');
        }

        if ($organisateur) {
            // Ajouter le filtre pour les sorties dont l'utilisateur est l'organisateur
            $queryBuilder
                ->andWhere('s.organisateur = :userId')
                ->setParameter('userId', $user);
        }

        if ($inscrit) {
            // Ajouter le filtre pour les sorties auxquelles l'utilisateur est inscrit
            $queryBuilder
                ->andWhere(':userId MEMBER OF s.users')
                ->setParameter('userId', $user);
        }

        if ($pasInscrit) {
            // Ajouter le filtre pour les sorties auxquelles l'utilisateur n'est pas inscrit
            $queryBuilder
                ->andWhere(':userId NOT MEMBER OF s.users')
                ->setParameter('userId', $user);
            $queryBuilder
                ->andWhere('e.libelle IN (:etats)')
                ->setParameter('etats', ['Ouverte', 'Activité en cours']);
        }

        if ($sortiesPassees) {
            // Ajouter le filtre pour les sorties passées
            $queryBuilder
                ->andWhere('e.libelle = :etatPassee')
                ->setParameter('etatPassee', 'Passée');
        }

        if ($dateRechercheDebut && $dateRechercheFin) {
            // Ajouter le filtre pour les sorties comprises entre les dates de recherche
            $queryBuilder
                ->andWhere('s.date >= :dateRechercheDebut')
                ->andWhere('s.date <= :dateRechercheFin')
                ->setParameter('dateRechercheDebut', $dateRechercheDebut)
                ->setParameter('dateRechercheFin', $dateRechercheFin);
        }

        if ($campusId) {
            // Ajouter le filtre pour les sorties du campus sélectionné
            $queryBuilder
                ->andWhere('c.id = :campusId')
                ->setParameter('campusId', $campusId);
        }

        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }
}
