<?php


namespace App\Tests;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    //Smoke testing
    //preliminary testing to reveal simple failures severe enough to reject a prospective software release
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertResponseIsSuccessful();
    }

    public function urlProvider(): Generator
    {
        yield ['/api'];
    }
}