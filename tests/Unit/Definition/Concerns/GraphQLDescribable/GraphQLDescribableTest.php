<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Definition\Concerns\GraphQLDescribable;

use GraphQL\Type\Definition\Description;
use Worksome\GraphQLHelpers\Definition\Concerns\GraphQLDescribable;

#[Description('Dummy enum description')]
enum DummyEnum
{
    use GraphQLDescribable;

    #[Description('PascalCase description')]
    case PascalCase;

    #[Description('SCREAMING_SNAKE_CASE description')]
    case SCREAMING_SNAKE_CASE; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case; // phpcs:ignore

    case NoDescription;
}

it('can convert an enum to the correct case for GraphQL', function (DummyEnum $enum, string $description) {
    expect($enum->description())->toBe($description);
})->with([
    [DummyEnum::PascalCase, 'PascalCase description'],
    [DummyEnum::SCREAMING_SNAKE_CASE, 'SCREAMING_SNAKE_CASE description'],
    [DummyEnum::snake_case, 'snake_case description'],
    [DummyEnum::NoDescription, 'No description'],
]);
