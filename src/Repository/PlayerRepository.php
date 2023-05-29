<?php

namespace App\Repository;

use App\Entity\Player;
use App\Entity\PlayerTeamInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 *
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function save(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Player $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return NativeQuery
     */
    public function findInactivePlayersQuery(): NativeQuery
    {
        $sql = "SELECT p.id,
       p.name,
       p.surname,
       (select count(pt.id)
        from player_team pt
        where pt.player_id = p.id
          and (pt.state = 'active' or pt.state = 'in_market')) as countActive
FROM player p
         LEFT JOIN player_team p1_ ON p.id = p1_.player_id
HAVING countActive = 0
ORDER BY p.id ASC";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('name', 'name');
        $rsm->addScalarResult('surname', 'surname');

        return $this->getEntityManager()->createNativeQuery($sql, $rsm);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countInactivePlayers(NativeQuery $query): int
    {
        $sqlInitial = $query->getSQL();

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addScalarResult('count', 'count');

        $sqlCount = 'SELECT COUNT(*) AS count FROM (' . $sqlInitial . ') AS item';
        $qCount = $this->getEntityManager()->createNativeQuery($sqlCount, $rsm);

        //dd($sqlCount->get)

        return (int)$qCount->getSingleScalarResult();
    }

    /**
     * @param NativeQuery $query
     * @param int $page
     * @param int $limit
     * @return float|int|mixed|string
     */
    public function getPaginateNativeData(NativeQuery $query, int $page, int $limit): mixed
    {
        $query->setSQL($query->getSQL() . ' limit ' . (($page - 1) * $limit) . ', ' . $limit);

        return $query->getResult();
    }
}
