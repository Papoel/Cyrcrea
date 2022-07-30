<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nommez votre adresse',
                'label_attr' => ['class' => 'text-muted text-uppercase'],
                'attr' => ['placeholder' => 'La Batcave'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez donner un nom à votre adresse'
                    ])
                ]
            ])
            ->add('company', TextType::class, [
                'required' => false,
                'label' => 'nom de Société',
                'label_attr' => ['class' => 'text-muted text-uppercase'],
                'attr' => ['placeholder' => 'Renseigner le nom de votre entreprise']
            ])
            ->add('address', TextType::class, [
                'label' => 'adresse',
                'label_attr' => ['class' => 'text-muted text-uppercase'],
                'attr' => ['placeholder' => '1 rue de la paix'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez renseigner une adresse'
                    ])
                ]
            ])
            ->add('complement', TextType::class, [
                'required' => false,
                'label' => 'complément d\'adresse',
                'label_attr' => ['class' => 'text-muted text-uppercase'],
                'attr' => ['placeholder' => 'Appartement B11'],
            ])
            ->add('city', TextType::class, [
                'label' => 'ville',
                'label_attr' => ['class' => 'text-muted text-uppercase'],
                'attr' => ['placeholder' => 'Gotham City'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Renseigner votre Ville'
                    ])
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'code postal',
                'label_attr' => ['class' => 'text-muted text-uppercase'],
                'attr' => ['placeholder' => '59000'],
                'constraints' => [
                    new Length(
                        [
                            'max' => 5,
                            'maxMessage' => 'Votre code postal ne doit pas excéder {{ limit }} caractères.',
                        ]
                    ),
                    new NotBlank([
                        'message' => 'Un code postal est attendu'
                    ])
                ],
            ])
            // Importer la liste des pays (Api Rest Country ?)
            ->add('country', CountryType::class, [
                'label' => 'pays',
                'label_attr' => ['class' => 'text-muted text-uppercase'],
                'attr' => ['placeholder' => 'France'],
                'choice_loader' => null,
                'choices' => [
                    'Allemagne' => 'Allemagne',
                    'Belgique' => 'Belgique',
                    'France' => 'France',
                    'Luxembourg' => 'luxembourg',
                ],
                'preferred_choices' => ['France' => 'France'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez chercher dans la liste votre pays de résidence ou de livraison'
                    ])
                ]
            ])

            ->add('phone', TextType::class, [
                'label' => 'téléphone',
                'label_attr' => ['class' => 'text-muted text-uppercase'],
                'attr' => ['placeholder' => '0610203040'],
                'constraints' => [
                    new Length(
                        [
                            'max' => 10,
                            'maxMessage' => 'Votre numéro de téléphone ne doit pas excéder {{ limit }} caractères.',
                        ]
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
