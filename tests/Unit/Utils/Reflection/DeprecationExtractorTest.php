<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Utils\Reflection;

use LogicException;
use ReflectionEnumUnitCase;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DeprecationExtractorDummyEnum;
use Worksome\GraphQLHelpers\Utils\Reflection\DeprecationExtractor;

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

it('throws when an enum case has more than one deprecated attribute', function () {
    $reflection = new ReflectionEnumUnitCase(DeprecationExtractorDummyEnum::class, 'DeprecatedTwice');

    DeprecationExtractor::extract($reflection);
})->throws(LogicException::class, DeprecationExtractor::MULTIPLE_DEPRECATIONS_DISALLOWED);
