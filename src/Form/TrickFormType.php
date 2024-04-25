<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickCategory;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrickFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name')
      ->add('description')
      ->add('trickCategory', EntityType::class, [
        'class' => TrickCategory::class,
        'choice_label' => 'name',
      ])
      ->add('images', CollectionType::class, [
        'label' => 'Images',
        'entry_type' => ImageFormType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false,
        'constraints' => [
          new Count(min: 1, minMessage: 'Vous devez ajouter au moins une image.', groups: ['new', 'edit']),
          new Valid(),
        ],
      ])
      ->add('videos', CollectionType::class, [
        'label' => 'Vidéos',
        'entry_type' => VideoFormType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false,
        'constraints' => [
          new Count(min: 1, minMessage: 'Vous devez ajouter au moins une vidéo.', groups: ['new', 'edit'])
        ]
      ])
      ->add('valider', SubmitType::class, [
        'label' => 'Valider'
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Trick::class,
      'validation_groups' => []
    ]);
  }
}
