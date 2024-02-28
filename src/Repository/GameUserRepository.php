<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\GameUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameUser>
 *
 * @method GameUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameUser[]    findAll()
 * @method GameUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameUser::class);
    }

//    /**
//     * @return GameUser[] Returns an array of GameUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GameUser
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ; 
//    }
    public function showMyGames(int $idUser, string $orderBy,string $direction): array
    {

        $queryBuilder = $this->createQueryBuilder('gu');

        $queryBuilder
            ->select('gu', 'g')
            ->innerJoin('gu.game', 'g')
            ->where('gu.user = :userId')
            ->setParameter('userId', $idUser)
            ->orderBy($orderBy, $direction);
        $result = $queryBuilder->getQuery()->getArrayResult();

        return $result;
    }

    public function hasUserAddedGame(User $user, Game $game): bool
    {
        $query = $this->createQueryBuilder('gu')
            ->select('COUNT(gu.id)')
            ->where('gu.user = :user')
            ->andWhere('gu.game = :game')
            ->setParameter('user', $user)
            ->setParameter('game', $game)
            ->getQuery();

        return $query->getSingleScalarResult() > 0;
    }
}
