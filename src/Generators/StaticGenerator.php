<?php

declare(strict_types=1);

namespace Photon\Generators;

interface StaticGenerator
{
    public static function printTo(string $file): void;
}
