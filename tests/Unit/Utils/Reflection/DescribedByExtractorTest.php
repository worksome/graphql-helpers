<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Utils\Reflection;

use LogicException;
use ReflectionEnumUnitCase;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DescribedByExtractorDummyEnum;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DescribedByExtractorDummyEnumMissingDescriber;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DescribedByExtractorDummyEnumUsingDescribableConcern;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DescribedByExtractorDummyEnumWithNonStringReturnType;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DescribedByExtractorDummyEnumWithNullDescription;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DescribedByExtractorDummyEnumWithoutAttribute;
use Worksome\GraphQLHelpers\Utils\Reflection\DescribedByExtractor;

it('can resolve descriptions from describer', function () {
    $reflection = new ReflectionEnumUnitCase(DescribedByExtractorDummyEnum::class, 'PascalCase');

    expect(DescribedByExtractor::extract($reflection))->toBe('Description from the describer');
});

it('returns null when the enum has no describer attribute', function () {
    $reflection = new ReflectionEnumUnitCase(DescribedByExtractorDummyEnumWithoutAttribute::class, 'PascalCase');

    expect(DescribedByExtractor::extract($reflection))->toBeNull();
});

it('returns null when the describer returns null', function () {
    $reflection = new ReflectionEnumUnitCase(DescribedByExtractorDummyEnumWithNullDescription::class, 'PascalCase');

    expect(DescribedByExtractor::extract($reflection))->toBeNull();
});

it('does not recurse when the describer is provided by the describable concern', function () {
    $reflection = new ReflectionEnumUnitCase(DescribedByExtractorDummyEnumUsingDescribableConcern::class, 'PascalCase');

    expect(DescribedByExtractor::extract($reflection))->toBe('Pascal case');
});

it('throws a LogicException when the describer method does not exist', function () {
    $reflection = new ReflectionEnumUnitCase(
        DescribedByExtractorDummyEnumMissingDescriber::class,
        'PascalCase'
    );

    DescribedByExtractor::extract($reflection);
})->throws(
    LogicException::class,
    sprintf(
        'The describer method `description` does not exist on `%s`',
        DescribedByExtractorDummyEnumMissingDescriber::class
    )
);

it('throws a LogicException when the describer does not return a string', function () {
    $reflection = new ReflectionEnumUnitCase(
        DescribedByExtractorDummyEnumWithNonStringReturnType::class,
        'PascalCase'
    );

    DescribedByExtractor::extract($reflection);
})->throws(
    LogicException::class,
    sprintf(
        'The describer method `description` on `%s` must return a string',
        DescribedByExtractorDummyEnumWithNonStringReturnType::class
    )
);
