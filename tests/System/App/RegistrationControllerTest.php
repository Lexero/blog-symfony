<?php

declare(strict_types=1);

namespace App\Tests\System\App;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister()
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $client->getCrawler()->selectButton('submit_btn')->form();
        $form['registration_form[name]'] = 'username';
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[password][first]'] = '123123';
        $form['registration_form[password][second]'] = '123123';
        $form['registration_form[terms]'] = true;

        $client->submit($form);

        $this->assertResponseRedirects('/login');
    }

    public function testVerifyUserEmail()
    {
        $client = static::createClient();
        $client->request('GET', '/verify/email/12345');

        $this->assertResponseRedirects('/blog');
    }
}
