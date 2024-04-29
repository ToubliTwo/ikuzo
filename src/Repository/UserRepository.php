<?php

namespace App\Repository;

use App\DTO\UserAvecPseudoNomPrenomDTO;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function findById($id): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->leftJoin('u.campus', 'c')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByCampus($campus): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.campus = :campus')
            ->setParameter('campus', $campus)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return UserAvecPseudoNomPrenomDTO[]
     */
    public function findBySorties($sorties): array
    {
        /*dd(
             this->getEntityManager()->createQuery(<<<DQL
             SELECT u
             FROM App\Entity\User u
             LEFT JOIN u.sorties s
             WHERE s = :sorties
             DQL)->getResult())*/ //construction de la requete en DQL

        return $this->createQueryBuilder('u')
            ->select('NEW App\\DTO\\UserAvecPseudoNomPrenomDTO($u.id, $u.pseudo, $u.nom, $u.prenom)')
            ->leftJoin('u.sorties', 's')
            ->andWhere('s = :sorties')
            ->setParameter('sorties', $sorties)
            ->getQuery()
            ->getResult();
    }

    public function paginateUsers(int $page, int $limite): Paginator
    {
      return new Paginator($this->createQueryBuilder('u')
          ->orderBy('u.id', 'ASC')
          ->getQuery()
          ->setFirstResult(($page - 1) * $limite)
          ->setMaxResults($limite)
          ->setHint(Paginator::HINT_ENABLE_DISTINCT, false),
      false
      );
    }
    //dans le controller, injecter Request $request dans la methode
    // $page = $request->query->getInt('page', 1);
    // $limite = 2;
    // $user = $userRepository->paginateUsers($page, $limite);
    // $maxPages = ceil($user->count() / $limite); arrondissement au superieur
    // return $this->render('user/index.html.twig', [
    //     'users' => $user,
    //     'maxPages' => $maxPages,
    //     'page' => $page

    //dans le twig
    //<div class="d-flex">
    //  {% if page > 1 %}
    //      <a href="{{ path('lien de la page', {'page': page - 1}) }}" class="btn btn-primary">Page précedente</a>
    //  {% endif %}
    //  {% if page < maxPage %}
    //      <a href="{{ path('lien de la page', {'page': page + 1}) }}" class="btn btn-primary">Page suivante</a>
    //  {% endif %}
    //</div>

    //on peut utiliser knpPaginatorBundle pour la pagination
    //composer require knplabs/knp-paginator-bundle

}
