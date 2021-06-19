<?php


namespace App\Tests;


use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        exec("php bin/console doctrine:fixtures:load --no-interaction");
    }
}