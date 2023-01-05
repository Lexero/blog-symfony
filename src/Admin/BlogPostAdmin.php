<?php

namespace App\Admin;

use App\Entity\BlogPost;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BlogPostAdmin extends AbstractAdmin
{
    private TokenStorageInterface $tokenStorage;

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('show');
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('id', null, [
            'attr' => [
                'readonly' => true,
            ],
        ])
            ->add('title', TextType::class)
            ->add('slug', TextType::class)
            ->add('body', TextType::class)
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('id')
            ->add('title')
            ->add('slug')
            ->add('createdAt')
            ->add(
                'author',
                null,
                [
                    'field_options' => [
                        'choice_label' => 'name',
                    ],
                ],
            )
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('title')
            ->add('slug')
            ->add('createdAt')
            ->add('author',
                null,
                [
                    'associated_property' => 'name',
                    'admin_code' => 'sonata.user',
                    'route' => [
                        'name' => 'edit'
                    ]
                ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                ],
            ])
        ;
    }

    protected function configureFormOptions(array &$formOptions): void
    {
        parent::configureFormOptions($formOptions);
        $formOptions['constraints'] = [
            new UniqueEntity([
                'fields' => ['slug'],
            ]),
        ];
    }

    protected function createNewInstance(): object
    {
        return new BlogPost($this->tokenStorage->getToken()->getUser());
    }

    public function setTokenStorage(TokenStorageInterface $tokenStorage): void
    {
        $this->tokenStorage = $tokenStorage;
    }

}