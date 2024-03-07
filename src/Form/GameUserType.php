<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\GameUser;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comments',TextareaType::class,[
                'required' => false,
                'attr' => [
                    'class' => 'text-black flex flex-col w-full',
                ],
            ])
            ->add('rate', IntegerType::class,[
                'attr' => [
                    'min' => 0,
                    'max' => 20,
                    'class' => 'mb-4 text-black flex flex-col w-full',
                ],
                
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Want to play' => 'Want To Play',
                    'Playing' => 'Playing',
                    'Finished' => 'Finished',
                    'Dropped' => 'Dropped',
                ],
                'attr' => [
                    
                    'class' => 'mb-4 flex flex-col w-full statusInput',
                ],
                'required' => false,
            ])
            ->add('playtime', IntegerType::class,[
                'required' => false,
                'attr' => [

                    'class' => ' mb-4 text-black flex flex-col w-full',
                ],
            ])
            ->add('datePlayed', DateType::class,[
                'required' => false,
                'attr' => [

                    'class' => 'mb-4 text-black flex-col w-full',
                ],
            ])
            ->add('platform', TextType::class,[
                'required' => false,
                'attr' => [

                    'class' => 'mb-4 text-black flex-col w-full',
                ],
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
