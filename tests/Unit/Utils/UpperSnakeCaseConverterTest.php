<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Utils;

use Worksome\GraphQLHelpers\Utils\UpperSnakeCaseConverter;

it('can convert a name to UPPER_SNAKE_CASE', function (string $name, string $expected) {
    expect(UpperSnakeCaseConverter::convert($name))->toBe($expected);
})->with([
    'pascal case' => ['PascalCase', 'PASCAL_CASE'],
    'macro case' => ['SCREAMING_SNAKE_CASE', 'SCREAMING_SNAKE_CASE'],
    'snake case' => ['snake_case', 'SNAKE_CASE'],
    'camel case' => ['camelCase', 'CAMEL_CASE'],
    'single word' => ['Main', 'MAIN'],
    'acronym' => ['ID', 'ID'],
    'acronym with digits' => ['IR35', 'IR35'],
    'mixed digits' => ['A1B2', 'A1B2'],
]);

it('preserves leading underscores', function (string $name, string $expected) {
    expect(UpperSnakeCaseConverter::convert($name))->toBe($expected);
})->with([
    'digits only' => ['_123', '_123'],
    'digit prefixed word' => ['_4SC', '_4SC'],
    'underscored word' => ['_Main', '_MAIN'],
    'multiple underscores' => ['__DOUBLE', '__DOUBLE'],
]);
