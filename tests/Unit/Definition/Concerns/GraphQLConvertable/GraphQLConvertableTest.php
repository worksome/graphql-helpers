<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Definition\Concerns\GraphQLConvertable;

use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\Concerns\GraphQLConvertable\DummyEnum;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\Concerns\GraphQLConvertable\PascalOnlyDummyEnum;

it('can convert an enum to the correct case for GraphQL', function (DummyEnum $enum, string $graphQLValue) {
    expect($enum->toGraphQLValue())->toBe($graphQLValue);
})->with([
    [DummyEnum::PascalCase, 'PASCAL_CASE'],
    [DummyEnum::MACRO_CASE, 'MACRO_CASE'],
    [DummyEnum::snake_case, 'SNAKE_CASE'],
    [DummyEnum::IR35, 'IR35'],
    [DummyEnum::_123, '_123'],
]);

it('can convert a GraphQL value to an enum', function (string $graphQLValue, PascalOnlyDummyEnum|null $enum) {
    expect(PascalOnlyDummyEnum::tryFromGraphQLValue($graphQLValue))->toBe($enum);
})->with([
    ['PASCAL_CASE', PascalOnlyDummyEnum::PascalCase],
    ['IR35', PascalOnlyDummyEnum::IR35],
    ['_123', PascalOnlyDummyEnum::_123],
    ['__NON_EXISTENT', null],
]);
