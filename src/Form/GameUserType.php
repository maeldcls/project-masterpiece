<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\GameUser;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comments',TextareaType::class,[
                'required' => false,
            ])
            ->add('rate', IntegerType::class,[
                'attr' => [
                    'min' => 0,
                    'max' => 20,
                ],
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Want to play' => 'want_to_play',
                    'Playing' => 'playing',
                    'Finished' => 'finished',
                    'Dropped' => 'dropped',
                ],
                'required' => false,
            ])
            ->add('playtime', IntegerType::class,[
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameUser::class,
        ]);
    }
}
