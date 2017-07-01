<?php

namespace App\Service;

/**
 * @author Florian Weber <florian.weber@fweber.info>
 */
final class SecureStringService
{
    public function generate()
    {
        return bin2hex(random_bytes(32));
    }
}
