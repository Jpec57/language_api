<?php


namespace App\Tests;


use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    const JPEC_TEST_TOKEN = "JeSuisUnToken";

    protected function setUp(): void
    {
        parent::setUp();
        echo "\nReloading database...\n";
        exec("php bin/console doctrine:fixtures:load --no-interaction");
    }
}