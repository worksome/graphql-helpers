<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Utils\Reflection;

use Error;
use ReflectionEnumUnitCase;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\GraphQLNameExtractorDummyEnum;
use Worksome\GraphQLHelpers\Utils\Reflection\GraphQLNameExtractor;

it(
    'can extract the GraphQL name override from an enum case',
    function (GraphQLNameExtractorDummyEnum $enum, string|null $name) {
        $reflection = new ReflectionEnumUnitCase($enum, $enum->name);

        expect(GraphQLNameExtractor::extract($reflection))->toBe($name);
    }
)->with([
    [GraphQLNameExtractorDummyEnum::Overridden, 'CUSTOM_NAME'],
    [GraphQLNameExtractorDummyEnum::NotOverridden, null],
]);

it(
    'resolves the GraphQL name of a case, falling back to its converted name',
    function (GraphQLNameExtractorDummyEnum $enum, string $name) {
        expect(GraphQLNameExtractor::forCase($enum))->toBe($name);
    }
)->with([
    [GraphQLNameExtractorDummyEnum::Overridden, 'CUSTOM_NAME'],
    [GraphQLNameExtractorDummyEnum::NotOverridden, 'NOT_OVERRIDDEN'],
]);

it('throws when an enum case has more than one GraphQL name attribute', function () {
    $reflection = new ReflectionEnumUnitCase(GraphQLNameExtractorDummyEnum::class, 'OverriddenTwice');

    GraphQLNameExtractor::extract($reflection);
})->throws(Error::class, 'must not be repeated');
