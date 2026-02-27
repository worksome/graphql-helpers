<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Utils\Reflection;

use GraphQL\Type\Definition\Deprecated;
use ReflectionEnumUnitCase;
use Worksome\GraphQLHelpers\Utils\Reflection\DeprecationExtractor;

enum DeprecationExtractorDummyEnum
{
    #[Deprecated('This is deprecated.')]
    case Deprecated;

    case NotDeprecated;
}

it(
    'can extract the deprecation reason from an enum case',
    function (DeprecationExtractorDummyEnum $enum, string|null $reason) {
        $reflection = new ReflectionEnumUnitCase($enum, $enum->name);

        expect(DeprecationExtractor::extract($reflection))->toBe($reason);
    }
)->with([
    [DeprecationExtractorDummyEnum::Deprecated, 'This is deprecated.'],
    [DeprecationExtractorDummyEnum::NotDeprecated, null],
]);
