<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
    public function testHomepageIsAccessible(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Vérifie que la page a un code 200
        $this->assertResponseIsSuccessful();

        // Vérifie la présence d'un élément clé dans la page
        $this->assertSelectorTextContains('h1', 'Connexion');
    }
}
