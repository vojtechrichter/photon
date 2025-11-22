<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

\Photon\Kernel::create()
    ->fromDirs([__DIR__ . '/test'])
    ->buildDir(__DIR__ . '/photon-build')
    ->boot();
