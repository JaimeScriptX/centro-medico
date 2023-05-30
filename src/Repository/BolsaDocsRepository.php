<?php

namespace App\Repository;

use App\Entity\BolsaDocs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BolsaDocs>
 *
 * @method BolsaDocs|null find($id, $lockMode = null, $lockVersion = null)
 * @method BolsaDocs|null findOneBy(array $criteria, array $orderBy = null)
 * @method BolsaDocs[]    findAll()
 * @method BolsaDocs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BolsaDocsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BolsaDocs::class);
    }

    public function add(BolsaDocs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BolsaDocs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}