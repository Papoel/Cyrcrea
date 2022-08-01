<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => ['class' => 'text-muted text-capitalize'],
                'attr' => [
                    'placeholder' => 'Bruce',
                ],
            ])

            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'label_attr' => ['class' => 'text-muted text-capitalize'],
                'attr' => [
                    'placeholder' => 'Wayne',
                ],
            ])

            ->add('email', EmailType::class, [
                'label' => 'email',
                'label_attr' => ['class' => 'text-muted text-capitalize'],
                'attr' => [
                    'placeholder' => 'batman@gotham.city',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez renseigner votre email',
                    ]),
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'checked' => true,
                ],
                'label_attr' => [
                    'class' => 'text-muted text-decoration-underline',
                ],
                'label' => 'Accepter les CGU',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\'utilisation pour vous inscrire.',
                    ]),
                ],
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options' => [
                    'label' => 'Mot de passe',
                    'label_attr' => ['class' => 'text-muted'],
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'label_attr' => ['class' => 'text-muted'],
                ],

                'invalid_message' => 'Les mots de passe doivent être identique',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length(
                        [
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères.',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                            'maxMessage' => 'Votre mot de passe ne peut pas comporter plus de {{ limit }} caractères.',
                        ]
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
