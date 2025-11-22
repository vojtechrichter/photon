<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

// Migrate to symfony command

$depCollector = new \Vojtechrichter\StaticPhpDepCompiler\DependencyCollector();
