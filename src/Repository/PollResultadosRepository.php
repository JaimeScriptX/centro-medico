<?php

namespace App\Repository;

use App\Entity\PollResultados;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PollResultados>
 * @method PollResultados|null find($id, $lockMode = null, $lockVersion = null)
 * @method PollResultados|null findOneBy(array $criteria, array $orderBy = null)
 * @method PollResultados[]    findAll()
 * @method PollResultados[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class PollResultadosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PollResultados::class);
    }

    public function findResultados()
    {
        $em = $this->getEntityManager();
        
        $query = $em->createQuery('SELECT r FROM App:PollResultados r order by r.resultadoId');
        
        return( $query->getResult() );
    }

    public function devolver()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT resultado_id, fecha, hora, email, respuesta, pregunta  FROM poll_polls p, poll_respuestas resp, poll_resultados r, user u, poll_preguntas pr WHERE p.poll_id = r.poll_id AND r.respuesta_id = resp.respuesta_id AND p.user_id = u.id AND pr.pregunta_id = resp.pregunta_id order by r.resultado_id desc limit 6";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        $resultado = $result->fetchAllAssociative();

        return($resultado);
    }

    public function porcentaje()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "WITH counted_responses AS (
            SELECT 
                respuesta, 
                COUNT(*) as total_responses
            FROM 
                poll_resultados res, poll_respuestas resp
            WHERE
                res.respuesta_id = resp.respuesta_id
            GROUP BY 
                respuesta
          )
          SELECT 
              respuesta, 
              total_responses * 100.0 / (SELECT SUM(total_responses) FROM counted_responses) AS porcentaje
          FROM 
              (SELECT * FROM counted_responses ORDER BY total_responses DESC LIMIT 5) AS top_five_responses;";          

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        $resultado = $result->fetchAllAssociative();

        return($resultado);
    }

     public function ResultadosEncuestaPorcentaje($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT pregunta, respuesta, COUNT(*) as porcentaje FROM poll_resultados r, poll_respuestas resp, poll_preguntas pr WHERE r.respuesta_id = resp.respuesta_id AND resp.pregunta_id = pr.pregunta_id AND r.poll_id = $id GROUP BY pregunta, respuesta;";

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();
        $resultado = $result->fetchAllAssociative();

        return($resultado);
    }


    
}