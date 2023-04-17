<?php

namespace App\Domain;

class GenerateUuid
{
    /**
     * @throws \Exception
     */
    public static function generate(?string $data = null): string
    {
        $data = $data ?? random_bytes(16);
        assert(16 == strlen($data));

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0F | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3F | 0x80);

        // Output the 36 characters UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
