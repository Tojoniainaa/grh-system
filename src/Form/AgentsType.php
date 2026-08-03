<?php

namespace App\Form;

use App\Entity\Agents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class AgentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Identité
            ->add('matricule', TextType::class, [
                'label' => 'Matricule *',
                'attr' => ['placeholder' => 'Ex: AG-2024-001'],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom *',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom *',
            ])
            ->add('sexe', ChoiceType::class, [
                'label' => 'Sexe *',
                'choices' => [
                    'Masculin' => 'M',
                    'Féminin' => 'F',
                    'Autre' => 'Autre',
                ],
                'placeholder' => 'Choisir...',
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('lieuNaissance', TextType::class, [
                'label' => 'Lieu de naissance',
                'required' => false,
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'required' => false,
            ])
            ->add('adresseEmail', EmailType::class, [
                'label' => 'Adresse e-mail',
                'required' => false,
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Âge',
                'required' => false,
            ])
            ->add('contact', TextType::class, [
                'label' => 'Contact',
                'required' => false,
            ])

            // Parents
            ->add('pere', TextType::class, [
                'label' => 'Nom du père',
                'required' => false,
            ])
            ->add('pereDecede', ChoiceType::class, [
                'label' => 'Père décédé ?',
                'choices' => [
                    'Non' => 'non',
                    'Oui' => 'oui',
                ],
                'required' => false,
                'placeholder' => 'Choisir...',
            ])
            ->add('mere', TextType::class, [
                'label' => 'Nom de la mère',
                'required' => false,
            ])
            ->add('mereDecede', ChoiceType::class, [
                'label' => 'Mère décédée ?',
                'choices' => [
                    'Non' => 'non',
                    'Oui' => 'oui',
                ],
                'required' => false,
                'placeholder' => 'Choisir...',
            ])

            // CIN
            ->add('cin', TextType::class, [
                'label' => 'Numéro CIN',
                'required' => false,
            ])
            ->add('dateCin', DateType::class, [
                'label' => 'Date de délivrance CIN',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('lieuDelivranceCin', TextType::class, [
                'label' => 'Lieu de délivrance CIN',
                'required' => false,
            ])

            // Fichiers
            ->add('nomPhotos', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'La photo est obligatoire']),
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Formats autorisés : JPG, PNG, WEBP',
                    ]),
                ],
            ])
            ->add('nomPdf', FileType::class, [
                'label' => 'Document PDF',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Seuls les fichiers PDF sont autorisés',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agents::class,
        ]);
    }
}
