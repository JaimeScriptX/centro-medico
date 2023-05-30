<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Especialidades;
use App\Entity\Medico;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GestionMedicosController extends AbstractController
{
    /**
     * @Route("/admin/listar_medicos",  name="listar_medicos")
     */
    public function listarMedicos()
    {
        $medicos = $this->getDoctrine()
            ->getRepository(Medico::class)
            ->findAll();

        if (!$medicos) {
            throw $this->createNotFoundException(
                'No product found for id ');
        }

        return $this->render('admin/GestionMedicos/listar.html.twig', array('medicos' => $medicos));
    }

    /**
     * @Route("/edit_medico/{id}/{search}", defaults={"search" = null }, name="edit_medico")
     */
    public function editMedico(Request $request, $id, $search)
    {

        $session = $request->getSession();

        $medicos = $this->getDoctrine()
            ->getRepository(Medico::class)
            ->findOneById($id);

        if (!$medicos) {
            throw $this->createNotFoundException(
                'No item found for id ');
        }

        if (!$session->get('edit_id') || $session->get('edit_id') != $id) {
            $session->set('edit_id', $id);

            $especialidades = array();
            foreach ($medicos->getEspecialidades() as $item) {
                $especialidades[] = array('id' => $item->getId(), 'name' => $item->getName());
            }
            $session->set('edit_especialidades', $especialidades);

        }

        // Lista de autores
        $lista = array();

        foreach ($session->get('edit_especialidades') as $item) {

            $lista[$item['name']] = $item['id'];
        }

        // lista de escritores
        if (isset($search)) {
           
            $cadena = '%' . $search . '%';
            $em = $this->getDoctrine()->getManager();

            $query = $em->createQuery("SELECT n FROM App:Especialidades n WHERE n.name LIKE :searchterm ")
                ->setParameter('searchterm', $cadena);

            $especialidades = $query->getResult();
        } else {
            $especialidades = $this->getDoctrine()
                ->getRepository(Especialidades::class)
                ->findAll();
        }

        $list = array();
        foreach ($especialidades as $item) {
            $list[$item->getName()] = $item->getId();
        }

        $form = $this->createFormBuilder();
        $form->add('id', TextType::class, 
                ['data' => $medicos->getId(),
                'attr' => ['class' => 'form-control', 'readonly' => true,
                           'style' => 'width: 60%']]);

        $form->add('nombre', TextType::class, 
                ['data' => $medicos->getNombre(),
                'attr' => ['class' => 'form-control',
                           'style' => 'width: 60%']]);
        $form->add('papellido', TextType::class,
                ['data' => $medicos->getPapellido(),
                'attr' => ['class' => 'form-control',
                           'style' => 'width: 60%']]);
        $form->add('sapellido', TextType::class,
                ['data' => $medicos->getSapellido(),
                'attr' => ['class' => 'form-control',
                           'style' => 'width: 60%']]);


        $form->add('especialidades', ChoiceType::class, 
                ['choices' => $lista, 'multiple' => true, 'required' => false,
                'attr' => ['class' => 'form-control']]);

        $form->add('Search', TextType::class, 
                ['data' => isset($search) ? $search : '', 'required' => false]);
        
        $form->add('especialidad', ChoiceType::class, 
                ['choices' => $list, 'multiple' => true, 'required' => false,
                'attr' => ['class' => 'form-control']]);

        $form->add('Add', SubmitType::class, 
                ['attr' => ['class' => 'btn btn-primary',
                            'style' => 'margin-top: 10px']]);
        $form->add('Remove', SubmitType::class,
                ['attr' => ['class' => 'btn btn-danger',
                            'style' => 'margin-top: 10px']]);
        $form->add('Buscar', SubmitType::class,
                ['attr' => ['class' => 'btn btn-success']]);
        $form->add('Save', SubmitType::class, 
                ['attr' => ['class' => 'btn btn-primary']]);
        $form->add('Delete', SubmitType::class,
                ['attr' => ['class' => 'btn btn-danger',
                            'style' => 'margin-top: 10px']]);
        $form = $form->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $medicos->setNombre($data['nombre']);
            $medicos->setPapellido($data['papellido']);
            $medicos->setSapellido($data['sapellido']);

            if ($form->get('Add')->isClicked()) {
                foreach ($data['especialidad'] as $item) {
                    $especialidad = $this->getDoctrine()
                        ->getRepository(Especialidades::class)
                        ->findOneById($item);

                    $especialidades = $session->get('edit_especialidades');
                    $especialidades[] = array('id' => $especialidad->getId(), 'name' => $especialidad->getName());
                    $session->set('edit_especialidades', $especialidades);
                }
                
                return $this->redirectToRoute('edit_medico', ['id' => $data['id']]);

            } elseif ($form->get('Remove')->isClicked()) {
                
                $posiciones = array();
                foreach ($data['especialidades'] as $item) {
                    $pos = 0;
                    foreach ($session->get('edit_especialidades') as $elemento) {
                        printf("</br> %s [%s] [%s]</br> ", $pos, $elemento['id'], $item);
                         if ($elemento['id'] == $item) {
                            printf("</br> %s [%s] [%s] ok</br> ", $pos, $elemento['id'], $item);
                            $posiciones[] = $pos;
                        }
                        $pos++;
                    }
                }
                //die();
                $especialidades = $session->get('edit_especialidades');
                foreach ($posiciones as $pos) {
                    unset($especialidades[$pos]);
                }
                $session->set('edit_especialidades', $especialidades);
                return $this->redirectToRoute('edit_medico', ['id' => $data['id']]);
            } elseif ($form->get('Buscar')->isClicked()) {
                return $this->redirectToRoute('edit_medico', ['id' => $data['id'], 'search' => $data['Search']]);
            } elseif ($form->get('Delete')->isClicked()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($medicos);
                $em->flush();
                return $this->redirectToRoute('listar_medicos');
            } elseif ($form->get('Save')->isClicked()) {
                $posiciones = array();
                $pos = 0;
                // Borra los autores que no estan en sesion
                foreach ($medicos->getEspecialidades() as $item) {
                    if (!in_array($item->getId(), $session->get('edit_especialidades'))) {
                        $posiciones[] = $pos;
                        $pos++;
                    }
                }
                arsort($posiciones); // orden inverso posiciones
                foreach ($posiciones as $pos) {
                    $medicos->getEspecialidades()->remove($pos);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($medicos);
                }

                // Añade los autores que estan en sesion
                foreach ($session->get('edit_especialidades') as $item) {
                    $especialidad = $this->getDoctrine()
                        ->getRepository(Especialidades::class)
                        ->findOneById($item['id']);
                    if (!$medicos->getEspecialidades()->contains($especialidad)) {
                        $medicos->addEspecialidad($especialidad);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($medicos);
                    }
                }

                // Borro Sesion
                //$session->clear();
                $session->remove('edit_especialidades');
                $session->remove('edit_id');

                $em = $this->getDoctrine()->getManager();
                $em->persist($medicos);

                $em->flush();
                return $this->redirectToRoute('listar_medicos');

            }

        } else {
            return $this->render('admin/GestionMedicos/medico.html.twig', array('form' => $form->createView(), "libro" => $medicos));
        }

    }

        /**
     * @Route("/admin/new_medico/{search}", defaults={"search" = null }, name="newMedico")
     */
    public function newLibro(Request $request, $search)
    {

        $session = $request->getSession();

        if ($session->get('new_medico') == null) {
            $medicos = new Medico();
            $session->set('new_medico', $medicos);
            $session->set('new_especialidades', array());
        } else {
            $medicos = $session->get('new_medico');
        }

        $lista = array();
        // Lista de autores
        if ($session->get('new_especialidades') != null) {
            foreach ($session->get('new_especialidades') as $item) {
                $lista[$item['name']] = $item['id'];
            }
        }

        // lista de escritores
        if (isset($search)) {
            $cadena = '%' . $search . '%';
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery("SELECT n FROM App:Especialidades n WHERE n.name LIKE :searchterm ")
                ->setParameter('searchterm', $cadena);
            $especialidad = $query->getResult();
        } else {
            $especialidad = $this->getDoctrine()
                ->getRepository(Especialidades::class)
                ->findAll();
        }

        $list = array();
        foreach ($especialidad as $item) {
            $list[$item->getName()] = $item->getId();
        }

        $form = $this->createFormBuilder();
        $form->add('id', HiddenType::class, ['label'=>false]);
        $form->add('nombre', TextType::class, 
                ['attr' => ['class' => 'form-control',
                           'style' => 'width: 60%']]);

        $form->add('papellido', TextType::class, 
                ['attr' => ['class' => 'form-control',
                           'style' => 'width: 60%']]);

        $form->add('sapellido', TextType::class,
                ['attr' => ['class' => 'form-control',
                           'style' => 'width: 60%']]);

        $form->add('especialidades', ChoiceType::class, 
                ['choices' => $lista, 'multiple' => true, 'required' => false,
                'attr' => ['class' => 'form-control']]);

        $form->add('Search', TextType::class, 
                ['data' => isset($search) ? $search : '', 'required' => false]);
        
        $form->add('especialidad', ChoiceType::class, 
                ['choices' => $list, 'multiple' => true, 'required' => false,
                'attr' => ['class' => 'form-control']]);

        $form->add('Add', SubmitType::class, 
                ['attr' => ['class' => 'btn btn-primary',
                            'style' => 'margin-top: 10px']]);
        $form->add('Remove', SubmitType::class,
                ['attr' => ['class' => 'btn btn-danger',
                            'style' => 'margin-top: 10px']]);
        $form->add('Buscar', SubmitType::class,
                ['attr' => ['class' => 'btn btn-success']]);
        $form->add('Save', SubmitType::class, 
                ['attr' => ['class' => 'btn btn-primary']]);
        $form->add('Delete', SubmitType::class,
                ['attr' => ['class' => 'btn btn-danger',
                            'style' => 'margin-top: 10px']]);
        $form = $form->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $medicos->setNombre($data['nombre']);
            $medicos->setPapellido($data['papellido']);
            $medicos->setSapellido($data['sapellido']);

            if ($form->get('Add')->isClicked()) {
                foreach ($data['especialidad'] as $item) {
                    $especialidad = $this->getDoctrine()
                        ->getRepository(Especialidades::class)
                        ->findOneById($item);
                    $especialidades = $session->get('new_especialidades');
                    $especialidades[] = array('id' => $especialidad->getId(), 'name' => $especialidad->getName());
                    $session->set('new_autores', $especialidades);
                }
                $session->set('medicos', $medicos);
                return $this->redirectToRoute('newMedico');
            } elseif ($form->get('Remove')->isClicked()) {
                
                $posiciones = array();
                foreach ($data['especialidades'] as $item) {

                    $pos = 0;
                    foreach ($session->get('new_autores') as $elemento) {
                       
                        if ($item == $elemento['id']) {
                            $posiciones[] = $pos;
                        }
                        $pos++;
                    }
                }
                $especialidades = $session->get('new_especialidades');
                foreach ($posiciones as $pos) {
                    unset($autores[$pos]);
                }
                $session->set('new_especialidades', $especialidades);
                $session->set('new_medico', $medicos);
                return $this->redirectToRoute('newLibro');
            } elseif ($form->get('Buscar')->isClicked()) {
                $session->set('new_libro', $libro);
                return $this->redirectToRoute('newLibro', ['search' => $data['Search']]);
            } elseif ($form->get('Save')->isClicked()) {

                // Añade los autores que estan en sesion
                foreach ($session->get('new_especialidades') as $item) {
                    $especialidad = $this->getDoctrine()
                        ->getRepository(Especialidades::class)
                        ->findOneById($item['id']);
                    $medicos->addAutor($especialidad);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($especialidad);
                    $em->persist($medicos);
                }
            }

            // Borro Sesion
            //$session->clear();
            $session->remove('new_especialidades');
            $session->remove('new_medico');

     
            $em = $this->getDoctrine()->getManager();
            $em->persist($medicos);
            $em->flush();

            return $this->redirectToRoute('listar_medicos');

        } else {
            return $this->render('admin/GestionMedicos/medico.html.twig', array( 'medicos' => $medicos, 'form' => $form->createView()));
        }

    }

    /**
     * @Route("/admin/error/{error}", defaults={"error" = null }, name="libro_error")
     */
    public function error($error)
    {
      
        return $this->render('biblioteca/error.html.twig');
    }
}
