<?php

namespace App\Controller;

use App\Entity\BolsaBolsas;
use App\Entity\BolsaDocs;
use App\Entity\BolsaSolicitudes;
use App\Repository\BolsaBolsasRepository;
use App\Repository\BolsaSolicitudesRepository;
use App\Repository\BolsaPuestosRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;


use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Validator\DniDatabase;
use App\Validator\EmailDatabase;
use App\Validator\NombreDatabase;
use App\Validator\TelefonoDatabase;
use App\Validator\DireccionDatabase;
use App\Validator\CodigoPostalDatabase;

class BolsaController extends AbstractController
{
    /**
     * @Route("/bolsa", name="bolsa")
     */
    public function index(Request $request, BolsaBolsasRepository $bolsasR, SluggerInterface $slugger): Response
    {
       $bolsa = $bolsasR->getBolsas();

        $lista = array();

        foreach($bolsa as $item){
            $lista[$item['puesto']] = $item['bolsa_id'];
        }

        $user = $this->getUser();

        if ($user) {
            $placeholder = $user->getEmail();
            $readonly = true;
        } else {
            $placeholder = '';
            $readonly = false;
        }

        

        $formbuilder = $this->createFormBuilder()
            ->add('bolsa_puestos', ChoiceType::class, [
                'label' => 'Puesto',
                'choices' => $lista,
                'placeholder' => 'Selecciona un puesto',
                'attr' => [
                    'class' => 'form-select bg-white border-0',
                    'style' => 'height: 55px',
                ],
            ])
            ->add('dni', TextType::class, [
                'label' => 'DNI',
                'attr' => [
                    'placeholder' => 'Introduce tu DNI',
                    'class' => 'form-control bg-white border-0',
                    'style' => 'height: 55px',
                ],
                'constraints' => [new DniDatabase()],
            ])
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'attr' => [
                    'placeholder' => 'Introduce tu nombre',
                    'class' => 'form-control bg-white border-0',
                    'style' => 'height: 55px',
                ],
                'constraints' => [new NombreDatabase()],
            ])
            ->add('apellidos', TextType::class, [
                'label' => 'Apellidos',
                'attr' => [
                    'placeholder' => 'Introduce tus apellidos',
                    'class' => 'form-control bg-white border-0',
                    'style' => 'height: 55px',
                ],
            ])
            ->add('email', EmailType::class,
                [
                    'attr' => ['class' => 'form-control bg-white border-0', 'style' => 'height: 55px',
                                'readonly'=> $readonly, 'placeholder' => 'example@example.com'],
                    'constraints' => [new EmailDatabase()],
                    'label' => 'Email',
                    'required' => false,
                    'data' => $placeholder,
                ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección',
                'attr' => [
                    'placeholder' => 'Introduce tu dirección',
                    'class' => 'form-control bg-white border-0',
                    'style' => 'height: 55px',
                ],
                'constraints' => [new DireccionDatabase()],
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'attr' => [
                    'placeholder' => 'Introduce tu teléfono',
                    'class' => 'form-control bg-white border-0',
                    'style' => 'height: 55px',
                ],
                'constraints' => [new TelefonoDatabase()],
            ])
            ->add('poblacion', TextType::class, [
                'label' => 'Población',
                'attr' => [
                    'placeholder' => 'Introduce tu población',
                    'class' => 'form-control bg-white border-0',
                    'style' => 'height: 55px',
                ],
            ])
            ->add('codigo_postal', TextType::class, [
                'label' => 'Código Postal',
                'attr' => [
                    'placeholder' => 'Introduce tu código postal',
                    'class' => 'form-control bg-white border-0',
                    'style' => 'height: 55px',
                ],
                'constraints' => [new CodigoPostalDatabase()],
            ])
            ->add('doc', FileType::class, [
                'label' => 'Curriculum',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Introduce tu curriculum',
                    'class' => 'form-control-file bg-white border-0',
                    'accept' => '.pdf',
                ],
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube un archivo PDF válido',
                    ])
                ],
            ])
            ->add('detalles', TextareaType::class, [
                'label' => 'Detalles',
                'attr' => [
                    'placeholder' => 'Introduce tus detalles',
                    'class' => 'form-control bg-white border-0',
                    'style' => 'height: 55px',
                ],
            ])
            ->add('aceptar', SubmitType::class, [
                'label' => 'Enviar',
                'attr' => [
                    'class' => 'btn btn-primary w-100 py-3',
                ],
            ])
            ;

        $form = $formbuilder->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            $doc = new BolsaDocs();
            $file = $form->get('doc')->getData();
            if($file){
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($filename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('files_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $doc->setName($newFilename);
                $doc->setDescripcion($data['detalles']);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($doc);
                $entityManager->flush();
            }

            $bolsaB = $bolsasR->find($data['bolsa_puestos']);

            $bolsa = new BolsaSolicitudes();

            $bolsa->setBolsa($bolsaB);
            $bolsa->setDni($data['dni']);
            $bolsa->setNombre($data['nombre']);
            $bolsa->setApellidos($data['apellidos']);
            $bolsa->setEmail($data['email']);
            $bolsa->setDireccion($data['direccion']);
            $bolsa->setTelefono($data['telefono']);
            $bolsa->setPoblacion($data['poblacion']);
            $bolsa->setCodigoPostal($data['codigo_postal']);

            if($file){
                $bolsa->setDoc($doc);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bolsa);
            $entityManager->flush();

            $this->addFlash('success', 'Solicitud enviada correctamente');
            return $this->redirectToRoute('bolsa');
        }

        return $this->render('bolsa/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/bolsa", name="bolsa_admin")
     */
    public function bolsaAdmin(BolsaSolicitudesRepository $bolsaSolicitudesRepository, BolsaPuestosRepository $bolsaPuestosRepository): Response
    {
        $bolsas = $bolsaSolicitudesRepository->findAll();

        $puestos = $bolsaPuestosRepository->findAll();

        return $this->render('admin/Bolsa/admin.html.twig', [
            'bolsas' => $bolsas,
            'puestos' => $puestos,
        ]);
    }
}
