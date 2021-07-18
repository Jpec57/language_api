<?php

namespace App\Trait;

trait TestingTrait
{
    public function assertArraysAreSimilar(array $expectedArr, array $actual){
        $expectedKeys = array_keys($expectedArr);
        foreach ($expectedKeys as $key){
            $this->assertArrayHasKey($key, $actual);
            if (is_array($expectedArr[$key])){
                $this->assertArraysAreSimilar($expectedArr[$key], $actual[$key]);
            } else {
                $this->assertEquals($expectedArr[$key], $actual[$key]);
            }
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        echo "\nReloading database...\n";
        exec("php bin/console doctrine:fixtures:load --no-interaction");
    }
}