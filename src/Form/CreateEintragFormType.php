<?php

namespace App\Form;

use App\Entity\Beitraege;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateEintragFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Der Titel muss min. {{ limit }} Zeichen lang sein!',
                        'max' => 255,
                        'maxMessage' => 'Der Titel darf nur max. {{ limit }} Zeichen lang sein!',
                    ]),
                    new NotBlank([
                        'message' => 'Bitte gib einen Titel für den Eintrag ein!',
                    ])
                ]
            ])
            ->add('inhalt_md', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 65535,
                        'maxMessage' => 'Der Markdown Text darf nur max. {{ limit }} Zeichen lang sein!',
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Beitraege::class,
        ]);
    }
}
