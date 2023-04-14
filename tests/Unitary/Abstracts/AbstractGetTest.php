<?php

namespace App\Tests\Unitary\Abstracts;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractGetTest extends KernelTestCase
{
    abstract public function testGet(): void;

    abstract public function testGetOne(): void;

    abstract public function testGeNotExist(): void;
}
