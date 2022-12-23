<?php

namespace App\Admin;

use App\Entity\BlogPost;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BlogPostAdmin extends AbstractAdmin
{
    private TokenStorageInterface $tokenStorage;
    //TODO убрать кликабельность и добавить вместо этого кнопку edit
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
            ->add('author') //TODO убираем, нужно чтобы он сюда подтягивался из имени юзера создавшего пост
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid
            ->add('id')
            ->add('title')
            ->add('slug')
            ->add('createdAt')
            ->add('author')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id')
            ->addIdentifier('title')
            ->addIdentifier('slug')
            ->addIdentifier('createdAt')
            ->addIdentifier('author')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('title')
            ->add('slug')
            ->add('createdAt')
            ->add('author')
        ;
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