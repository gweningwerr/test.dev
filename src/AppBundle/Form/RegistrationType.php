<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'required' => false,
                'label' => 'Аватар'
            ])
            ->add('email', EmailType::class, [
                'label' => 'email'
            ])
            ->add('username', null, [
                'label' => 'Имя'
            ])
            ->add('sex', ChoiceType::class, [
                'label' => 'Пол',
                'choices'  => [
                    'Выберите' => null,
                    'Мужской' => 1,
                    'Женский' => 2
                ]
            ])
            ->add('birth', BirthdayType::class , [
                'label' => 'Дата рождения',
                'placeholder' => [
                    'year' => 'Год',
                    'month' => 'Месяц',
                    'day' => 'День',
                ],
                'format' => 'dd.MM.yyyy',
            ])
            ->add('city', null, [
                'label' => 'Город',
                'required' => false
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Адрес',
                'required' => false
            ])
            ->add('phone', null, [
                'label' => 'Телефон',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => [
                    'class' => 'btn btn-default'
                ]
            ])
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}