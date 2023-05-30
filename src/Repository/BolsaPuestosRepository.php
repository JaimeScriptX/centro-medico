<?php

namespace App\Repository;

use App\Entity\BolsaPuestos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BolsaPuestos>
 *
 * @method BolsaPuestos|null find($id, $lockMode = null, $lockVersion = null)
 * @method BolsaPuestos|null findOneBy(array $criteria, array $orderBy = null)
 * @method BolsaPuestos[]    findAll()
 * @method BolsaPuestos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BolsaPuestosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BolsaPuestos::class);
    }

    public function add(BolsaPuestos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BolsaPuestos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}