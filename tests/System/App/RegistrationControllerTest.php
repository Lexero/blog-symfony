<?php

declare(strict_types=1);

namespace App\Tests\System\App;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegistration()
    {
        $client = static::createClient();

        $client->request('GET', '/registration');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $client->getCrawler()->selectButton('submit_btn')->form();
        $form['registrationForm[name]'] = 'username';
        $form['registrationForm[email]'] = 'test@example.com';
        $form['registrationForm[password][first]'] = '123123';
        $form['registrationForm[password][second]'] = '123123';
        $form['registrationForm[terms]'] = true;

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
