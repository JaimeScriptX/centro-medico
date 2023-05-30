<?php

namespace App\Controller;

use App\Entity\Especialidades;
use App\Entity\Medico;
use App\Entity\Servicios;
use App\Entity\ServiciosContenido;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiciosController extends AbstractController
{
    /**
     * @Route("/servicios", name="servicios")
     */
    public function index(): Response
    {
        $servicios = $this->getDoctrine()->
                            getRepository(Servicios::class)
                            ->findAll();
        
        return $this->render('servicios/index.html.twig', [
            'servicios' => $servicios,
        ]);
    }

    /**
     * @Route("/servicio/descripcion/{id}/{title}", name="servicios_descripcion")
     */
    public function descripcion($id, $title) : Response
    {
        $contenido = $this->getDoctrine()->
                    getRepository(ServiciosContenido::class)
                    ->find($id);
        
        return $this->render('servicios/descripcion.html.twig', [
            'contenido' => $contenido,
            'title' => $title
        ]);
    }
}
