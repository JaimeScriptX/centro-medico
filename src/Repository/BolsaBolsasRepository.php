<?php

namespace App\Repository;

use App\Entity\BolsaBolsas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BolsaBolsas>
 *
 * @method BolsaBolsas|null find($id, $lockMode = null, $lockVersion = null)
 * @method BolsaBolsas|null findOneBy(array $criteria, array $orderBy = null)
 * @method BolsaBolsas[]    findAll()
 * @method BolsaBolsas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BolsaBolsasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BolsaBolsas::class);
    }

    public function add(BolsaBolsas $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BolsaBolsas $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getBolsas()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT * from bolsa_bolsas, bolsa_puestos where bolsa_bolsas.puesto_id = bolsa_puestos.puesto_id and bolsa_bolsas.inicio <= CURRENT_DATE and bolsa_bolsas.fin >=CURRENT_DATE";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        $resultado = $result->fetchAllAssociative();

        return($resultado);
    }
}