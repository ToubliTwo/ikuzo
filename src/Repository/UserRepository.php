<?php

namespace App\Repository;

use App\DTO\UserAvecPseudoNomPrenomDTO;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
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
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);

        $this->paginator = $paginator;
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
        return $this->createQueryBuilder(alias: 'u')
            ->andWhere(where: 'u.id = :id')
            ->leftJoin(join: 'u.campus',alias:  'c')
            ->setParameter(key: 'id',value:  $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByCampus($campus): array
    {
        return $this->createQueryBuilder(alias: 'u')
            ->andWhere(where: 'u.campus = :campus')
            ->setParameter(key: 'campus',value:  $campus)
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

        return $this->createQueryBuilder(alias: 'u')
            ->select(select: 'NEW App\\DTO\\UserAvecPseudoNomPrenomDTO($u.id, $u.pseudo, $u.nom, $u.prenom)')
            ->leftJoin(join: 'u.sorties',alias:  's')
            ->andWhere(where: 's = :sorties')
            ->setParameter(key: 'sorties',value:  $sorties)
            ->getQuery()
            ->getResult();
    }
    public function paginateUsers(int $page, int $limit): \Knp\Component\Pager\Pagination\PaginationInterface
    {
      return $this->paginator->paginate(
          $this->createQueryBuilder(alias: 'u'),
          $page,
          $limit
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
