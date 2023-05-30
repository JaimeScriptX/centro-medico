<?php

namespace App\Controller;

use App\Entity\Encuestas;
use App\Entity\PollEncuestas;
use App\Entity\PollPolls;
use App\Entity\PollPreguntas;
use App\Entity\PollRespuestas;
use App\Entity\PollResultados;
use App\Entity\User;
use App\Repository\PollEncuestasRepository;
use App\Repository\PollResultadosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EncuestaController extends AbstractController
{
    /**
     * @Route("/encuestas", name="encuestas")
     */
    public function index():Response
    {
        $encuestas = $this->getDoctrine()
            ->getRepository(PollEncuestas::class)
            ->findAll();

        return $this->render('encuesta/index.html.twig', [
            'encuestas' => $encuestas,
        ]);
    }

    /**
     * @Route("/admin/encuesta/resultados_generales", name="resultados")
     */
    public function resultadosGenerales(PollResultadosRepository $PollR): Response
    {
        $resultados = $PollR->devolver();

        $porcentajes = $PollR->porcentaje();

        return $this->render('admin/EncuestasResultados/resultados.html.twig', [
            'resultados' => $resultados,
            'porcentajes' => $porcentajes,
        ]);
    }

    /**
     * @Route("/admin/encuesta/resultados/{id}", name="resultados_encuesta")
     */
    public function resultadosEncuesta($id, PollResultadosRepository $PollR): Response
    {
        $resultados = $PollR->ResultadosEncuestaPorcentaje($id);

        return $this->render('encuesta/resultadoid.html.twig', [
            'resultados' => $resultados,
        ]);
    }

    /**
     * @Route("/encuesta/{id}", name="encuesta")
     */
    public function encuesta($id, Request $request, PollEncuestasRepository $encuesta): Response
    {
        $encuesta = $encuesta->name($id);
        
        $encuesta = $encuesta[0]['encuesta'];

        $preguntas = $this->getDoctrine()
            ->getRepository(PollPreguntas::class)
            ->findBy(['encuesta' => $id]);

        $formbuilder = $this->createFormBuilder();
        for ($i = 0; $i < count($preguntas); $i++) {

            $respuestas = $this->getDoctrine()
            ->getRepository(PollRespuestas::class)
            ->findBy(['pregunta' => $preguntas[$i]->getPreguntaId()]);

            $lista = array();
            
            foreach( $respuestas as $item )
            {
                $lista[ $item->getRespuesta() ] =  $item->getRespuestaId();
            }

            $formbuilder->add('Pregunta_' . $i, ChoiceType::class, [
                'label' => $preguntas[$i]->getPregunta(),
                'choices' => $lista,
                'expanded' => true,
                'multiple' => false,
                'choice_attr' => function($choiceValue, $key, $value) {
                    return ['style' => 'margin-bottom: 10px;'];
                },
            ]);
        }
            $formbuilder->add('enviar', SubmitType::class, [
                'label' => 'Enviar',
            ]);
            
            $form = $formbuilder->getForm();
         
            $form->handleRequest( $request );

        if($form->isSubmitted() && $form->isValid()) {
            
            $data = $form->getData();


            $poll = new PollPolls();
            $poll->setFecha(new \DateTime());
            $poll->setHora(new \DateTime());

            if($this->getUser() != null) {
                $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find($this->getUser()->getId());
                $poll->setUser($user);
                }
            else {
                $user = $this->getDoctrine()
                    ->getRepository(User::class)
                    ->find(3);
                $poll->setUser($user);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($poll);
            $entityManager->flush();

            for ($i = 0; $i < count($preguntas); $i++) {
                $intrespuesta = (int)$data['Pregunta_' . $i];

                $respuesta = $this->getDoctrine()
                    ->getRepository(PollRespuestas::class)
                    ->find($intrespuesta);

                $resultados = new PollResultados();
                $resultados->setPoll($poll);
                $resultados->setRespuesta($respuesta);
                $entityManager->persist($resultados);
                $entityManager->flush();
            }

            $this->addFlash('success', 'Encuesta enviada correctamente');
            return $this->redirectToRoute('encuestas');
        }

        return $this->render('encuesta/encuesta.html.twig', [
            'form' => $form->createView(),
            'encuesta' => $encuesta,
        ]);
    }

}
