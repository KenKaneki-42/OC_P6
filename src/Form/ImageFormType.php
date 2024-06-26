<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('file', FileType::class, [
        'required' => false,
        'label' => false,
        'attr' => ['class' => 'my-image'],
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
          new NotNull(message: 'Veuillez renseigner tous les champs images', groups: ['new'])
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Image::class,
    ]);
  }
}
