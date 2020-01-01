<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Subject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManageUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => ['ROLE_ADMIN' => 'ROLE_ADMIN', 'ROLE_STUDENT' => 'ROLE_STUDENT', 'ROLE_TEACHER' => 'ROLE_TEACHER'],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('subjects', EntityType::class, [
                'class' => Subject::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
