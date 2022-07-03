<?php

namespace App\Form;

use App\Entity\MainPageMarkdown;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class MainMDFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('markdown_md', TextareaType::class, [
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
            'data_class' => MainPageMarkdown::class,
        ]);
    }
}
