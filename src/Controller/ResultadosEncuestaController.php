<?php

namespace App\Controller;

use App\Repository\PollEncuestasRepository;
use App\Repository\PollPreguntasRepository;
use App\Repository\PollRespuestasRepository;
use App\Repository\PollResultadosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultadosEncuestaController extends AbstractController
{
    /**
    * @Route("/resultadosencuesta", name="resultados_ecnuesta")
    */
    public function resultados(PollEncuestasRepository $pollEncuestasRepository, PollPreguntasRepository $pollPreguntasRepository, PollRespuestasRepository $pollRespuestasRepository): Response
    {
        $preguntas = $pollPreguntasRepository->findAll();
        $respuestas = $pollRespuestasRepository->findAll();
    
        // Inicializar arrays para almacenar los resultados
        $resultadoIndividual = array();
        $resultadoPreguntas = array();
        $porcentaje = array();
    
        // Calcular el número de veces que cada respuesta ha sido seleccionada
        $resultados = $pollEncuestasRepository->findPreguntasRespuestas();
        foreach ($resultados as $r) {
            $preguntaid = $r['pregunta_id'];
            $respuestaid = $r['respuesta_id'];
            $total = $r['count(*)'];
    
            $resultadoIndividual[$preguntaid][$respuestaid] = $total;
                 
            if (isset($resultadoPreguntas[$preguntaid])) {
                $resultadoPreguntas[$preguntaid] += $total;
            } else {
                $resultadoPreguntas[$preguntaid] = $total;
            }
        }
    
        // Calcular el porcentaje de veces que se ha seleccionado cada respuesta
        foreach ($respuestas as $resp) {
            foreach ($preguntas as $preg) {
                $preguntaid = $preg->getPreguntaId();
                $respuestaid = $resp->getRespuestaId();
        
                if (isset($resultadoIndividual[$preguntaid][$respuestaid])) {
                    $porcentaje[$preguntaid][$respuestaid] = number_format(($resultadoIndividual[$preguntaid][$respuestaid] / $resultadoPreguntas[$preguntaid]) * 100, 2);
                } else {
                    $porcentaje[$preguntaid][$respuestaid] = 0;
                }
            }
        }

        var_dump(serialize($preguntas));
        
            // die();
        return $this->render('resultados_encuesta/index.html.twig', ['resulindiv' => $resultadoIndividual, 'prs' => $preguntas, 'resps' => $respuestas, 'totalesdecadapregunta' => $resultadoPreguntas, 'porcentaje' => $porcentaje]);
    }
}
