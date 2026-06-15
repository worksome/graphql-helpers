<?php

declare(strict_types=1);

use Worksome\CodingStyle\Sniffs\Enums\PascalCasingEnumCasesSniff;
use Worksome\CodingStyle\WorksomeEcsConfig;

return WorksomeEcsConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        PascalCasingEnumCasesSniff::class => [
            __DIR__.'/tests',
        ],
    ]);
