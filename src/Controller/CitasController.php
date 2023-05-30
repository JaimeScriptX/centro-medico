<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CitasFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Citas;
use App\Repository\CitasRepository;

class CitasController extends AbstractController
{
    /**
     * @Route("/citas", name="citas")
     */
    public function index(Request $request, CitasRepository $cita): Response
    {
        $citas = new Citas();
        $form = $this->createForm(CitasFormType::class, $citas);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $citas->setNombre($data->getNombre());
            $citas->setDni($data->getDni());
            $citas->setTelefono($data->getTelefono());
            $citas->setEmail($data->getEmail());
            $citas->setDireccion($data->getDireccion());
            $citas->setDetalles($data->getDetalles());
            $citas->setEspecialidad($data->getEspecialidad());

            $cita->add($citas, true);

            $this->addFlash('success', 'Cita creada correctamente');
            return $this->redirectToRoute('citas');
        }

        return $this->render('citas/index.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/admin/citas", name="ver_citas")
     */
    public function citas(Request $request, CitasRepository $cita): Response
    {
        $citas = $cita->findAll();

        return $this->render('admin/Citas/index.html.twig', [
            'citas' => $citas,

        ]);
    }
}

