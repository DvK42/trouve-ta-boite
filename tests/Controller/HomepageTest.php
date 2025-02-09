<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
    public function testHomepageIsAccessible(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Vérifie que la page a un code 200
        $this->assertResponseIsSuccessful();

        // Vérifie la présence d'un élément clé dans la page
        $this->assertSelectorTextContains('title', 'Trouve Ta Boîte - Bienvenue');
    }
}
