<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Definition\Concerns\GraphQLConvertable;

use GraphQL\Type\Definition\Description;
use Worksome\GraphQLHelpers\Definition\Concerns\GraphQLConvertable;

#[Description('Dummy enum description')]
enum DummyEnum
{
    use GraphQLConvertable;

    #[Description('PascalCase description')]
    case PascalCase;

    #[Description('MACRO_CASE description')]
    case MACRO_CASE; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case; // phpcs:ignore

    #[Description('UPPERCASE_NUMERIC description')]
    case IR35; // phpcs:ignore

    #[Description('numeric description')]
    case _123; // phpcs:ignore
}

it('can convert an enum to the correct case for GraphQL', function (DummyEnum $enum, string $graphQLValue) {
    expect($enum->toGraphQLValue())->toBe($graphQLValue);
})->with([
    [DummyEnum::PascalCase, 'PASCAL_CASE'],
    [DummyEnum::MACRO_CASE, 'MACRO_CASE'],
    [DummyEnum::snake_case, 'SNAKE_CASE'],
    [DummyEnum::IR35, 'IR35'],
    [DummyEnum::_123, '_123'],
]);

enum PascalOnlyDummyEnum: string
{
    use GraphQLConvertable;

    #[Description('PascalCase description')]
    case PascalCase = 'test';

    #[Description('UPPERCASE_NUMERIC description')]
    case IR35 = 'IR35';

    #[Description('numeric description')]
    case _123 = '123';
}

it('can convert a GraphQL value to an enum', function (string $graphQLValue, PascalOnlyDummyEnum|null $enum) {
    expect(PascalOnlyDummyEnum::tryFromGraphQLValue($graphQLValue))->toBe($enum);
})->with([
    ['PASCAL_CASE', PascalOnlyDummyEnum::PascalCase],
    ['IR35', PascalOnlyDummyEnum::IR35],
    ['_123', PascalOnlyDummyEnum::_123],
    ['__NON_EXISTENT', null],
]);
