<?php

declare(strict_types=1);

namespace App\Admin;

use App\Enum\UserRoleEnum;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('create')
            ->remove('show')
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
                    new Length(['min' => 3, 'max' => 100]),
                ],
            ])
            ->add('email', TextType::class, [
                'constraints' => [
                    new Length(['min' => 3, 'max' => 100]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices'  => UserRoleEnum::cases(),
                'multiple' => true,
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name')
            ->add('email')
            ->add('roles');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('name')
            ->add('email')
            ->add('roles', null, [
                'template' => 'admin/roles.html.twig',
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                ],
            ]);
    }

    protected function configureFormOptions(array &$formOptions): void
    {
        parent::configureFormOptions($formOptions);
        $formOptions['constraints'] = [
            new UniqueEntity([
                'fields' => ['email'],
            ]),
        ];
    }
}
