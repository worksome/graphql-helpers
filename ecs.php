<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Worksome\CodingStyle\Sniffs\Enums\PascalCasingEnumCasesSniff;
use Worksome\CodingStyle\WorksomeEcsConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

    WorksomeEcsConfig::setup($ecsConfig);

    $ecsConfig->skip(WorksomeEcsConfig::skips([
        PascalCasingEnumCasesSniff::class => [
            __DIR__.'/tests',
        ],
    ]));
};
