<?php

namespace App\Form;

use App\Entity\Mark;
use App\Entity\User;
use App\Entity\Subject;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class MarkType extends AbstractType
{
    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mark')
            ->add('outof')
            ->add('comment')
            ->add('subject', EntityType::class, [
                'class' => Subject::class,
                'query_builder' => function(EntityRepository $er)
                {
                    return $er->createQueryBuilder('s')
                        ->leftJoin('s.users', 'users')
                        ->addSelect('users')
                        ->where('users = :user')
                        ->setParameter('user', $this->user)
                    ;
                }
            ])
            ->add('student', EntityType::class, [
                'class' => User::class,
                'query_builder' => function(EntityRepository $er) 
                {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%"'.'ROLE_STUDENT'.'"%')
                    ;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mark::class,
        ]);
    }
}
