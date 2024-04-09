<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('firstname', TextType::class, ['label' => 'Nom'])
      ->add('lastname', TextType::class, ['label' => 'Prénom'])
      ->add('email', EmailType::class)
      ->add('plainPassword', RepeatedType::class, [
        'type' => PasswordType::class,
        'first_options' => [
          'attr' => ['autocomplete' => 'new-password'],
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a password',
            ]),
            new Length([
              'min' => 6,
              'minMessage' => 'Your password should be at least {{ limit }} characters',
              'max' => 4096,
            ]),
          ],
          'label' => 'Mot de passe',
        ],
        'second_options' => [
          'attr' => ['autocomplete' => 'new-password'],
          'label' => 'Confirmation de mot de passe',
        ],
        'invalid_message' => 'The password fields must match.',
      ])
      ->add('agreeTerms', CheckboxType::class, [
        'label' => 'Accepter les conditions',
        'mapped' => false,
        'constraints' => [
          new IsTrue([
            'message' => 'You should agree to our terms.',
          ]),
        ],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
      'validation_groups', ['password', 'Default']
    ]);
  }
}
