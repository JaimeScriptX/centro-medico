<?php

namespace App\Controller;

use App\Entity\Especialidades;
use App\Entity\Medico;
use App\Repository\MedicoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;

class CuadroMedicoController extends AbstractController
{
    /**
     * @Route("/cuadro_medico", name="cuadromedico")
     */
    public function index(Request $request, PaginatorInterface $paginaitor, MedicoRepository $medicoR): Response
    {
        $medicos = $this->getDoctrine()
            ->getRepository(Medico::class)
            ->findAll();

        $especialidades = $this->getDoctrine()
            ->getRepository(Especialidades::class)
            ->findAll();

        $formbuilder = $this->createFormBuilder();

        $formbuilder->add('buscar', SearchType::class, [
            'label' => 'Buscar',
            'required' => false,
            'label' => false,
            'attr' => [
                'placeholder' => 'Buscar por nombre',
                'class' => 'form-control border-primary w-50',
                'style' => 'width: 50%; height: 50px',
            ],
        ]);

        $formbuilder->add('especialidad', EntityType::class , [
            'class' => Especialidades::class,
            'choice_label' => 'name',
            'placeholder' => 'Seleccione una especialidad',
            'label' => false,
            'required' => false,
            'attr' => [
                'class' => 'form-select border-primary w-25',
                'style' => 'height: 50px',
            ],
        ]);

        $form = $formbuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if($data['buscar'] != null){
                
                $medicos = $this->getDoctrine()
                    ->getRepository(Medico::class)
                    ->findBy(['nombre' => $data['buscar']]);
            }

            if($data['especialidad'] != null){

                $medicos = $this->getDoctrine()
                        ->getRepository(Medico::class)
                        ->findEspecialidad($data['especialidad']->getId());
                        
            }

            if($data['buscar'] != null && $data['especialidad'] != null){
                $medicos = $this->getDoctrine()
                    ->getRepository(Medico::class)
                    ->findEspecialidadNombre($data['buscar'], $data['especialidad']->getId());
            }
        }
        
        $pagination = $paginaitor->paginate(
            $medicos,
            $request->query->getInt('page', 1),
            6);

        return $this->render('CuadroMedico/index.html.twig', [
            'especialidades' => $especialidades,
            'medicos' => $medicos,
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }
}





