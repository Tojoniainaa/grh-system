<?php

namespace App\Form;

use App\Entity\Agents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{
    TextType,
    ChoiceType,
    DateType,
    FileType
};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AgentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // === IDENTITÉ ===

            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'form-control',
                    'placeholder' => 'Nom'],
                'required' => false,
            ])

            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'form-control',
                    'placeholder' => 'Entrez le prénom'],
                'required' => false,
            ])
            ->add('sexe', ChoiceType::class, [
                'label' => 'Sexe',
                'placeholder' => 'Sélectionnez',
                'choices' => [
                    'Masculin' => 'M',
                    'Féminin' => 'F',
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'required' => true,
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
            ])
            ->add('lieuNaissance', TextType::class, [
                'label' => 'Lieu de naissance',
                'attr' => [
                    'form-control',
                    'placeholder' => 'Entrez le lieu de naissance'],
                'required' => false,
            ])

            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'form-control',
                    'placeholder' => 'Adresse'],
                'required' => false,
            ])

            // === PARENTS ===
            ->add('pere', TextType::class, [
                'label' => 'Nom du père',
                'attr' => [
                    'form-control',
                    'placeholder' => 'Nom complet du père'],
                'required' => false,
            ])
            ->add('pereDecede', ChoiceType::class, [
                'label' => 'Père décédé ?',
                'placeholder' => 'Sélectionnez',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'required' => false,
            ])
            ->add('mere', TextType::class, [
                'label' => 'Nom de la mère',
                'attr' => [
                    'form-control',
                    'placeholder' => 'Nom complet de la mère'],
                'required' => false,
            ])
            ->add('mereDecede', ChoiceType::class, [
                'label' => 'Mère décédée ?',
                'placeholder' => 'Sélectionnez',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'attr' => [
                    'class' => 'form-select'
                ],
                'required' => false,
            ])

            // === CIN ===
            ->add('cin', TextType::class, [
                'label' => 'Numéro CIN',
                'attr' => [
                    'form-control',
                    'placeholder' => 'Numéro de la CIN'],
                'required' => false,
            ])
            ->add('dateCin', DateType::class, [
                'label' => 'Date de délivrance',
                'widget' => 'single_text',
                'html5' => true,
                'required' => false,
            ])
            ->add('lieuDelivranceCin', TextType::class, [
                'label' => 'Lieu de délivrance',
                'attr' => [
                    'form-control',
                    'placeholder' => 'Lieu de délivrance du CIN'],
                'required' => false,
            ])
            ->add('contact', TextType::class, [
                'label' => 'Numéro de contact',
                'attr' => [
                    'form-control',
                    'placeholder' => 'Ex: 034 XX XXX XX'],
                'required' => false,
            ])

            // === DOCUMENTS ===
            ->add('nomPhotos', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'style' => 'display:none;',
                ],
            ])
            ->add('nomPdf', FileType::class, [
                'label' => 'Document PDF',
                'required' => false,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agents::class,
        ]);
    }
}
