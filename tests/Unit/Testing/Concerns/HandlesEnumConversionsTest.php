<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Testing\Concerns;

use UnitEnum;
use Worksome\GraphQLHelpers\Testing\Concerns\HandlesEnumConversions;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Testing\Concerns\DummyEnum;

it('can convert an enum to the correct case for GraphQL', function (UnitEnum $enum, string $graphQLValue) {
    $subject = new class() {
        use HandlesEnumConversions;
    };

    expect($subject->enumToGraphQL($enum))->toBe($graphQLValue);
})->with([
    [DummyEnum::PascalCase, 'PASCAL_CASE'],
    [DummyEnum::SCREAMING_SNAKE_CASE, 'SCREAMING_SNAKE_CASE'],
    [DummyEnum::snake_case, 'SNAKE_CASE'],
    [DummyEnum::IR35, 'IR35'],
    [DummyEnum::_123, '_123'],
]);
