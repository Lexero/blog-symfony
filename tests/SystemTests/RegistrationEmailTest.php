<?php

namespace App\Tests\SystemTests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationEmailTest extends WebTestCase
{
    public function testRegisterUserAndMailIsSentAndContentIsOk()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $this->assertEquals(1, $crawler->filter('#inputEmail')->count());
        $this->assertEquals(1, $crawler->filter('#inputPassword')->count());

//        $client->sendJson(
//            '/register',
//            [
//                "name" => "gdfsgfs",
//                "email" => "fsfdsfd@fgds.cfdfs",
//                "password" => [
//                    "first" => "123123",
//                    "second" => "123123"
//                ],
//                "terms" => "1",
//                "_token" => "4092b3cb5b9fce9ae162eca5a0.2eQF0TeKETUMdobeYV6u4QzHgwOXEGdokcAo2o7mO6A.4b1JiG_zRGxoDvSXKyzKuGSo4kneeg0p26MbmN-kUs2MjTe8X8Y8c1slvg"
//            ]
//        );
//
//        $this->assertResponseIsSuccessful();
//
//        $this->assertQueuedEmailCount(1);
//
//        $email = $this->getMailerMessage();
//
//        $this->assertEmailHtmlBodyContains($email, 'Click here to confirm Email');
//        $this->assertEmailTextBodyContains($email, 'Click here to confirm Email');
    }
}