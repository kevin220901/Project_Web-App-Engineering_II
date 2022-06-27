<?php

namespace App\Form;

use App\Entity\Wiki;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CreateWikiFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('wikiname', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Der Wikiname muss min. {{ limit }} Zeichen lang sein!',
                        'max' => 255,
                        'maxMessage' => 'Der Wikiname darf nur max. {{ limit }} Zeichen lang sein!',
                    ]),
                    new NotBlank([
                        'message' => 'Bitte gib den Namen des Wikis an!',
                    ])
                ]
            ])

            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
            ])

            ->add('startseite_md', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 65535,
                        'maxMessage' => 'Der Markdown Text darf nur max. {{ limit }} Zeichen lang sein!',
                    ]),
                ]
            ])
            ->add('privat_wiki')
            ->add('everyone_can_see')
            ->add('loggedin_can_see')
            ->add('can_user_request_to_join')
            ->add('loggedin_create_posts')
            ->add('loggedin_edit_posts')
            ->add('collab_edit_posts')
            ->add('allow_votes')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wiki::class,
        ]);
    }
}
