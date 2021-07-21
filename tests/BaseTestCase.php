<?php


namespace App\Tests;


use App\Trait\TestingTrait;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    use TestingTrait;
//    protected function setUp(): void
//    {
//        parent::setUp();
//        echo "\nReloading database...\n";
//        exec("php bin/console doctrine:fixtures:load --no-interaction");
//    }
}