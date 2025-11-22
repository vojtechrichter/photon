<?php

declare(strict_types=1);

namespace Photon\Generators;

final class BootstrapGenerator implements StaticGenerator
{
    private static function getBootstrapSnippet(): string
    {
        return <<<PHP
        <?php

        ini_set('opcache.enable', '1');
        ini_set('opcache.jit', '1255');
        ini_set('opcache.jit_buffer_size', '256M');
        ini_set('opcache.validate_timestamps', '0');
        ini_set('opcache.save_comments', '1'); // Necessary for attributes

        // ------------------------ ! Important Notice ! ------------------------
        
        // Set the opcache.preload directive pointing to the preload file in php.ini
        // Also, you need to set the user which will be used for the preload in the opcache.preload_user directive
        
        // Example:
        // File: php.ini
        
        // opcache.preload=/var/www/myapp/build/static-preload.php
        // opcache.preload_user=nginx

        PHP;
    }

    public static function printTo(string $file, ?string $content = null): void
    {
        file_put_contents($file, self::getBootstrapSnippet());
    }
}
