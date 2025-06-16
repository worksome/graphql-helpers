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

    #[Description('SCREAMING_SNAKE_CASE description')]
    case SCREAMING_SNAKE_CASE; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case; // phpcs:ignore

    #[Description('snake_case description')]
    case IR35; // phpcs:ignore
}

it('can convert an enum to the correct case for GraphQL', function (DummyEnum $enum, string $graphQLValue) {
    expect($enum->toGraphQLValue())->toBe($graphQLValue);
})->with([
    [DummyEnum::PascalCase, 'PASCAL_CASE'],
    [DummyEnum::SCREAMING_SNAKE_CASE, 'SCREAMING_SNAKE_CASE'],
    [DummyEnum::snake_case, 'SNAKE_CASE'],
    [DummyEnum::IR35, 'IR35'],
]);
