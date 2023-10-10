<?php

declare(strict_types=1);

namespace App\Tests\System\Admin;

use App\Tests\System\WebTestCase;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPagesTest extends WebTestCase
{
    private const ADMIN_EMAIL = "admin@your.com";

    private const BLACKLIST_URL_NAMES = [
        'sonata_admin_redirect',
        'sonata_admin_retrieve_form_element',
        'sonata_admin_append_form_element',
        'sonata_admin_short_object_information',
        'sonata_admin_set_object_field_value',
        'sonata_admin_search',
        'sonata_admin_retrieve_autocomplete_items',
    ];

    /**
     * @test
     * @throws Exception
     */
    public function testAllPagesResponseCode()
    {
        $client = $this->createAuthenticatedAdminClient(self::ADMIN_EMAIL);

        $router = static::$kernel->getContainer()->get('router');

        $routes = $router->getRouteCollection()->all();

        foreach ($routes as $routeName => $route) {
            if (
                in_array($routeName, self::BLACKLIST_URL_NAMES) ||
                !str_contains($routeName, 'admin_') ||
                !str_contains($routeName, 'sonata_')
            ) {
                continue;
            }

            $url = $router->generate($routeName);

            $client->request(Request::METHOD_GET, $url);

            $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode(), $routeName . ' | ' . $url);
        }
    }
}
