<?php
namespace AppBundle\Form;

use AppBundle\Helper\AppHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Название',
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Описание',
                'required' => true
            ])
            ->add('performer', EntityType::class, [
                'placeholder' => 'выберите исполнителя',
                'class' => 'AppBundle:User',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.id != :id')
                        ->setParameter('id', AppHelper::getUser()->getId())
                        ->orderBy('u.username', 'ASC');
                },
                'choice_label' => 'username',
                'label' => 'Исполнитель',
                'required' => true
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn btn-default'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Task',
            'csrf_protection' => false
        ]);
    }

    public function getName()
    {
        return 'app_task';
    }
}