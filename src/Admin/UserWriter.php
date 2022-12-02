<?php

declare(strict_types=1);


namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserWriter extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('email', TextType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('email');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('email');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('email');
    }
}
