<?php

namespace App\Controller;

use App\Entity\BolsaPuestos;
use App\Repository\BolsaBolsasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BolsaBolsas;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GestionBolsaController extends AbstractController
{
    /**
     * @Route("/gestionbolsa", name="gestion_bolsa")
     */
    public function listar(BolsaBolsasRepository $bolsa): Response
    {
        $bolsas = $bolsa->findAll();

        return $this->render('admin/gestion_bolsa/index.html.twig', [
            'bolsas' => $bolsas,
        ]);
    }

     /**
     * @Route("/gestionbolsa/editar/{id}", name="gestion_bolsa_editar")
     */
    public function editar(Request $request, BolsaBolsasRepository $bolsa): Response
    {

        $bolsas = $bolsa->find($request->get('id'));

        $puestos = $this->getDoctrine()->getRepository(BolsaPuestos::class)->findAll();

        $puestos_array = array();

        foreach ($puestos as $puesto) {
            $puestos_array[$puesto->getPuesto()] = $puesto->getPuestoId();
        }

        $formbuilder = $this->createFormBuilder()
        ->add('id', TextType::class, [
            'attr' => array('class' => 'form-control', 'readonly' => true),
            'data' => $bolsas->getBolsaId(),
        ])
        ->add('fecha_inicio', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'attr' => array('class' => 'form-control'),
            'data' => $bolsas->getInicio(),
        ])
        ->add('fecha_fin', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'attr' => array('class' => 'form-control'),
            'data' => $bolsas->getFin(),
        ])
        ->add('puesto',ChoiceType::class, [
            'choices' => 
            $puestos_array,
            'attr' => array('class' => 'form-control'),
            'data' => $bolsas->getPuesto()->getPuestoId(),
        ])
        ->add('enviar', SubmitType::class, [
            'attr' => array('class' => 'btn btn-primary'),
        ]);

        var_dump(serialize($request->get('id')));

        $form = $formbuilder->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $bolsaPuestos = $this->getDoctrine()->getRepository(BolsaPuestos::class)->find($data['puesto']);

            $bolsas = $this->getDoctrine()->getRepository(BolsaBolsas::class)->find($data['id']);

            $bolsas->setInicio($data['fecha_inicio']);
            $bolsas->setFin($data['fecha_fin']);
            $bolsas->setPuesto($bolsaPuestos);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bolsas);
            $entityManager->flush();

            return $this->redirectToRoute('gestion_bolsa');
        }

        return $this->render('admin/gestion_bolsa/bolsa.html.twig', [
            'form' => $form->createView(),
            'id' => $request->get('id'),
        ]);
    }


    /**
     * @Route("/gestionbolsa/nuevo", name="gestion_bolsa_nuevo")
     */
    public function nuevo(Request $request): Response
    {

        $formbuilder = $this->createFormBuilder()
        ->add('fecha_inicio', DateType::class, array(
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'attr' => array('class' => 'form-control'),
            
        ))
        ->add('fecha_fin', DateType::class, array(
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'attr' => array('class' => 'form-control'),
        ))
        ->add('puesto',ChoiceType::class, array(
            'choices' => array(
                'Administrativo' => 'Administrativo',
                'Técnico' => 'Técnico',
                'Profesional' => 'Profesional',
                'Directivo' => 'Directivo',
            ),
            'attr' => array('class' => 'form-control'),
        ));

        $form = $formbuilder->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $bolsaPuestos = $this->getDoctrine()->getRepository(BolsaPuestos::class)->find($data['puesto']);

            $bolsas = new BolsaBolsas();
            $bolsas->setInicio($data['fecha_inicio']);
            $bolsas->setFin($data['fecha_fin']);
            $bolsas->setPuesto($bolsaPuestos);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bolsas);
            $entityManager->flush();

            return $this->redirectToRoute('gestion_bolsa');
        }

        return $this->render('admin/gestion_bolsa/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gestionbolsa/eliminar/{id}", name="gestion_bolsa_eliminar")
     */
    public function eliminar(Request $request, BolsaBolsasRepository $bolsa): Response
    {
        $bolsas = $bolsa->find($request->get('id'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($bolsas);
        $entityManager->flush();

        return $this->redirectToRoute('gestion_bolsa');
    }
}
