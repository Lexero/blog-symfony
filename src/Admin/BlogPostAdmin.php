<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\BlogPost;
use App\Entity\User;
use Cocur\Slugify\SlugifyInterface;
use LogicException;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BlogPostAdmin extends AbstractAdmin
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly SlugifyInterface      $slug
    )
    {
        parent::__construct();
    }

    public function postPersist($object): void
    {
        $this->getRequest()->getSession()->getFlashBag()->add("success", "Blog post has been successfully created");
    }

    public function prePersist($object): void
    {
        if ($object instanceof BlogPost) {
            $object->setSlug($this->slug->slugify($object->getTitle()));
        }
    }

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
            ->add('body', TextareaType::class, [
                'attr' => ['style' => 'height:200px'],
            ]);
        $form->add('slug', TextType::class, [
            'attr' => [
                'readonly' => true,
            ],
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('title')
            ->add('createdAt')
            ->add(
                'author',
                null,
                [
                    'field_options' => [
                        'choice_label' => 'name',
                    ],
                ],
            );
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('title')
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
            ]);
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
        $token = $this->tokenStorage->getToken();

        if ($token === null) {
            throw new LogicException('The security token is not available. The user might not be authenticated.');
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            throw new LogicException('The current user is not available or is not of the expected type.');
        }

        return new BlogPost($user);
    }
}
