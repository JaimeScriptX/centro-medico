<?php

namespace App\Repository;

use App\Entity\Medico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medico>
 *
 * @method Medico|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medico|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medico[]    findAll()
 * @method Medico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medico::class);
    }

    public function add(Medico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Medico $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findEspecialidad(int $especialidadId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT m.id, e.name, m.nombre, m.papellido, m.sapellido  FROM especialidades e, medico_especialidades me, medico m WHERE e.id = me.especialidades_id AND me.medico_id = m.id AND e.id = :especialidadId";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['especialidadId' => $especialidadId]);
        $result = $stmt->executeQuery();
        $resultado = $result->fetchAllAssociative();


        return ($resultado);
    }

    public function findEspecialidadNombre(string $nombre, int $especialidadId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT m.id, e.name, m.nombre, m.papellido, m.sapellido  FROM especialidades e, medico_especialidades me, medico m WHERE e.id = me.especialidades_id AND me.medico_id = m.id AND e.id = :especialidadId AND m.nombre LIKE :nombre";

        $stmt = $conn->prepare($sql);
        $stmt->execute(['especialidadId' => $especialidadId, 'nombre' => $nombre]);
        $result = $stmt->executeQuery();
        $resultado = $result->fetchAllAssociative();

        return ($resultado);
    }
    //    /**
    //     * @return Medico[] Returns an array of Medico objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Medico
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
