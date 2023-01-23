<?php

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class AdminCRUDController extends CRUDController implements ServiceSubscriberInterface
{
    public function editAction(Request $request): Response
    {
        try {
            return parent::editAction($request);
        } catch (\RuntimeException $exception) {
            $this->addFlash('error', $exception->getMessage());
            return $this->redirect("/admin/app/blogpost/list");
        }
    }
}
