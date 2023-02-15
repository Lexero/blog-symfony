<?php

namespace App\Controller;

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
            $userId = $this->locker->getLockedBy($key);
            if ($userId !== null) {
                if ($authorizedUser->getId() === $userId) {
                    $this->locker->refreshLock($key, $userId);
                } else {
                    $this->addFlash('error', sprintf("User with ID %d working with that post", $userId));
                    return $this->listAction($request);
                }
            } else {
                $this->locker->setLock($key, $authorizedUser->getId());
            }
        }
        return parent::editAction($request);
    }
}
