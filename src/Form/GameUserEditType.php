<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\GameUser;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameUserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('isFav')
            // ->add('addedAt')
            // ->add('updatedAt')
            // ->add('playtime')
            // ->add('status')
            // ->add('rate')
            ->add('comments')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GameUser::class,
        ]);
    }
}
