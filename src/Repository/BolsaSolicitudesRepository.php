<?php

namespace App\Repository;

use App\Entity\BolsaSolicitudes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BolsaSolicitudes>
 *
 * @method BolsaSolicitudes|null find($id, $lockMode = null, $lockVersion = null)
 * @method BolsaSolicitudes|null findOneBy(array $criteria, array $orderBy = null)
 * @method BolsaSolicitudes[]    findAll()
 * @method BolsaSolicitudes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BolsaSolicitudesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BolsaSolicitudes::class);
    }

    public function add(BolsaSolicitudes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BolsaSolicitudes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}