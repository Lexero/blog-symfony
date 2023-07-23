<?php

declare(strict_types=1);

namespace App\Controller\Public;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController
{
    #[Route(path: '/', name: 'index')]
    public function index(): Response
    {
        return new RedirectResponse('/blog');
    }
}
