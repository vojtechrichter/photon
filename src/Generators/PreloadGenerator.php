<?php

declare(strict_types=1);

namespace Photon\Generators;

final class PreloadGenerator implements StaticGenerator
{
    public static function printTo(string $file, ?string $content = null): void
    {
        if ($content === null) {
            throw new \RuntimeException('No target file content provided ($content).');
        }

        file_put_contents($file, $content);
    }
}
