<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\Locker;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class AdminCRUDController extends CRUDController implements ServiceSubscriberInterface
{
    private Locker $locker;

    public function __construct(Locker $locker)
    {
        $this->locker = $locker;
    }

    public function editAction(Request $request): Response
    {
        $authorizedUser = $this->getUser();
        if ($authorizedUser instanceof User) {
            $key = $request->getRequestUri();
            $email = $this->locker->getLockedBy($key);
            if ($email !== null) {
                if ($authorizedUser->getEmail() === $email) {
                    $this->locker->refreshLock($key, $email);
                } else {
                    $this->addFlash('error', sprintf("User with email %d working with that entity", $email));
                    return $this->listAction($request);
                }
            } else {
                $this->locker->setLock($key, $authorizedUser->getId());
            }
        }
        return parent::editAction($request);
    }
}
