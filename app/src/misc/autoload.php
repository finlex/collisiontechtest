<?php

declare(strict_types=1);

// Super simple autoloader for src/ files
function custom_autoloader(string $class): void {
    // Remove App\ from namespace, replace other \ with /
    $class = str_replace(['App\\', '\\'], ['', '/'], $class);
    include(__DIR__ . '/../' . $class . '.php');
}

spl_autoload_register('custom_autoloader');
