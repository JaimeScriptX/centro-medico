<?php

namespace App\Form;

use App\Entity\Citas;
use App\Entity\Medico;
use App\Entity\Especialidades;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

use App\Validator\EmailDatabase;
use App\Validator\DniDatabase;
use App\Validator\TelefonoDatabase;
use App\Validator\DireccionDatabase;
use App\Validator\NombreDatabase;


class CitasFormType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        if ($user) {
            $placeholder = $user->getEmail();
            $readonly = true;
        } else {
            $placeholder = '';
            $readonly = false;
        }

        $builder
            ->add('email', EmailType::class,
                [
                    'attr' => ['class' => 'form-control bg-white border-0', 'style' => 'height: 55px',
                                'readonly'=> $readonly, 'placeholder' => 'example@example.com'],
                    'constraints' => [new EmailDatabase()],
                    'label' => 'Email',
                    'required' => false,
                    'data' => $placeholder,
                ])
            ->add('especialidad', EntityType::class, [
                'class' => Especialidades::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'form-select bg-white border-0', 
                           'style' => 'height: 55px',
                           'id' => 'especialidad',
                            ],
                'label' => 'Especialidad',
                'required' => true,
                'placeholder' => 'Seleccione una especialidad',
            ])
            ->add('dni', TextType::class,
                [
                    'attr' => ['class' => 'form-control bg-white border-0', 'style' => 'height: 55px',
                               'placeholder' => '123456789A'],
                    'constraints' => [new DniDatabase()],
                    'label' => 'Dni',
                    'required' => false,
                ])
            ->add('nombre', TextType::class,
                [
                    'attr' => ['class' => 'form-control bg-white border-0', 'style' => 'height: 55px',
                               'placeholder' => 'Nombre y apellidos'],
                    'constraints' => [new NombreDatabase()],
                    'label' => 'Nombre',
                    'required' => false,
                ])
            ->add('direccion', TextType::class,
                [
                    'attr' => ['class' => 'form-control bg-white border-0', 'style' => 'height: 55px',
                               'placeholder' => 'Calle, número, piso, puerta'],
                    'constraints' => [new DireccionDatabase()],
                    'label' => 'Dirección',
                    'required' => false,
                ] )
            ->add('telefono', TextType::class,
                [
                    'attr' => ['class' => 'form-control bg-white border-0', 'style' => 'height: 55px', 
                               'placeholder' => '653532456'],
                    'constraints' => [new TelefonoDatabase()],
                    'label' => 'Teléfono',
                    'required' => false,
                ])
            ->add('detalles', TextareaType::class,
                [
                    'attr' => ['class' => 'form-control bg-white border-0', 'style' => 'height: 55px', 
                               'placeholder' => 'Detalles de la cita'],
                    'label' => 'Detalles',
                    'required' => false,
                ])
            ->add('enviar', SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-primary w-100 py-3'],
                    'label' => 'Enviar',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Citas::class,
        ]);
    }
}
