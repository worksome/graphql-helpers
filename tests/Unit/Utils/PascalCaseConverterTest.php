<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Utils;

use Worksome\GraphQLHelpers\Utils\PascalCaseConverter;

it('can convert a name to PascalCase', function (string $name, string $expected) {
    expect(PascalCaseConverter::convert($name))->toBe($expected);
})->with([
    'macro case' => ['SCREAMING_SNAKE_CASE', 'ScreamingSnakeCase'],
    'snake case' => ['snake_case', 'SnakeCase'],
    'camel case' => ['camelCase', 'CamelCase'],
    'pascal case' => ['PascalCase', 'PascalCase'],
    'single word' => ['MAIN', 'Main'],
    'acronym with digits' => ['IR35', 'IR35'],
    'mixed digits' => ['A1B2', 'A1B2'],
]);

it('preserves leading underscores', function (string $name, string $expected) {
    expect(PascalCaseConverter::convert($name))->toBe($expected);
})->with([
    'digits only' => ['_123', '_123'],
    'digit prefixed word' => ['_4SC', '_4SC'],
    'underscored word' => ['_MAIN', '_Main'],
    'multiple underscores' => ['__DOUBLE', '__Double'],
]);
