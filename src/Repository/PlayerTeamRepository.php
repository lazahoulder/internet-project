<?php

namespace App\Repository;

use App\Entity\PlayerTeam;
use App\Entity\PlayerTeamInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlayerTeam>
 *
 * @method PlayerTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerTeam[]    findAll()
 * @method PlayerTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerTeam::class);
    }

    public function save(PlayerTeam $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PlayerTeam $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByTeamQuery($teamId): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->join('p.team', 't')
            ->andWhere('t.id = :val')
            ->setParameter('val', $teamId)
            ->andWhere($qb->expr()->neq('p.state', ':state'))
            ->setParameter('state', PlayerTeamInterface::INACTIVE_STATE)
        ;

        return $qb;
    }

    public function findPlayerQuery($teamId): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->join('p.team', 't')
            ->andWhere('t.id = :val')
            ->setParameter('val', $teamId)
            ->andWhere($qb->expr()->neq('p.state', PlayerTeamInterface::INACTIVE_STATE))
        ;

        return $qb;
    }

    public function findActivePlayersQb($teamId = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->join('p.team', 't')
            ->andWhere($qb->expr()->neq('p.state', ':inactiveState'))
            ->setParameter('inactiveState',PlayerTeamInterface::INACTIVE_STATE)
        ;

        if ($teamId) {
            $qb
                ->andWhere('t.id = :val')
                ->setParameter('val', $teamId)
            ;
        }

        return $qb;
    }

    public function findInMarketPlayersQb(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->andWhere($qb->expr()->eq('p.state', ':inactiveState'))
            ->setParameter('inactiveState',PlayerTeamInterface::IN_MARKET_STATE)
        ;

        return $qb;
    }
}
