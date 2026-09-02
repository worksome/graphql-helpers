<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Definition\Concerns\GraphQLDescribable;

use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\Concerns\GraphQLDescribable\DummyEnum;

it('can convert an enum to the correct case for GraphQL', function (DummyEnum $enum, string $description) {
    expect($enum->description())->toBe($description);
})->with([
    [DummyEnum::PascalCase, 'PascalCase description'],
    [DummyEnum::SCREAMING_SNAKE_CASE, 'SCREAMING_SNAKE_CASE description'],
    [DummyEnum::snake_case, 'snake_case description'],
    [DummyEnum::NoDescription, 'No description'],
    [DummyEnum::DocBlockOnly, 'Doc block only'],
]);
