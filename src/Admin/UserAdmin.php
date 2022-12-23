<?php

declare(strict_types=1);


namespace App\Admin;

use App\Enum\UserRoleTypeEnum;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    //TODO тоже самое в блог пост админ
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('create')
            ->remove('delete');
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('id', null, [
                'attr' => [
                    'readonly' => true,
                ],
            ])
            ->add('name', TextType::class, [
                'constraints' => [
                    new Length(['min' => 4, 'max' => 200]),
                ],
            ])
            ->add('email', TextType::class)
            ->add('roles', ChoiceType::class, [
                'choices'  => UserRoleTypeEnum::getValues(),
                'multiple' => true,
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('roles')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {//TODO Когда в списке кликаем по автору, то мы проваливаемся в этого юзера
        $list
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->addIdentifier('email')
            ->addIdentifier('roles')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('roles')
        ;
    }
}
