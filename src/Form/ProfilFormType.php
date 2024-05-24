<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfilFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('email', EmailType::class, [
        'disabled' => true,
      ])
      ->add('firstname', TextType::class)
      ->add('lastname', TextType::class)
      ->add('createdAt', DateTimeType::class, [
        'widget' => 'single_text',
        'disabled' => true,
      ])
      ->add('updatedAt', DateTimeType::class, [
        'widget' => 'single_text',
        'disabled' => true,
      ])
      ->add('isEnabled', CheckboxType::class, [
        'disabled' => true,
      ])
      ->add('isVerified', CheckboxType::class, [
        'disabled' => true,
      ])
      ->add('profilPictures', FileType::class, [
        'required' => false,
        'mapped' => false,
        'attr' => ['class' => 'profil-picture'],
        'constraints' => [
          new File([
            'maxSize' => '5M',
            'mimeTypes' => [
              'image/png',
              'image/jpg',
              'image/jpeg',
              'image/avif',
              'image/webp',
            ],
            'mimeTypesMessage' => 'Merci d\'ajouter une image au format jpg, jpeg, png, webp ou avif de maximum 5Mo.',
          ]),
        ],
      ])
      ->add('submit', SubmitType::class, [
        'label' => 'Sauvegarder'
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
