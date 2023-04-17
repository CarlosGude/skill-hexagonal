<?php

namespace App\Tests\Domanin\Unitary;

use App\Domain\GenerateUuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenerateUuidTest extends KernelTestCase
{
    /**
     * @throws \Exception
     */
    public function testValidateUuid(): void
    {
        $uuid = GenerateUuid::generate();

        $valid = preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid) != false;

        $this->assertIsString($uuid);
        $this->assertTrue($valid);
    }
}
