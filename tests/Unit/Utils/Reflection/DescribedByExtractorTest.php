<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Utils\Reflection;

use ReflectionEnumUnitCase;
use ValueError;
use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;
use Worksome\GraphQLHelpers\Utils\Reflection\DescribedByExtractor;

#[CasesDescribedBy(describer: 'description')]
enum DescribedByExtractorDummyEnum
{
    case PascalCase;

    public function description(): string
    {
        return 'Description from the describer';
    }
}

#[CasesDescribedBy(describer: 'description')]
enum DescribedByExtractorDummyEnumMissingDescriber
{
    case PascalCase;
}

#[CasesDescribedBy(describer: 'description')]
enum DescribedByExtractorDummyEnumWithNonStringReturnType
{
    case PascalCase;

    public function description(): int
    {
        return 123;
    }
}

it('can resolve descriptions from describer', function () {
    $reflection = new ReflectionEnumUnitCase(DescribedByExtractorDummyEnum::class, 'PascalCase');

    expect(DescribedByExtractor::extract($reflection))->toBe('Description from the describer');
});

it('throws a ValueError when the describer method does not exist', function () {
    $reflection = new ReflectionEnumUnitCase(
        DescribedByExtractorDummyEnumMissingDescriber::class,
        'PascalCase'
    );

    DescribedByExtractor::extract($reflection);
})->throws(
    ValueError::class,
    sprintf(
        'The describer method `description` does not exist on `%s`',
        DescribedByExtractorDummyEnumMissingDescriber::class
    )
);

it('throws a ValueError when the describer does not return a string', function () {
    $reflection = new ReflectionEnumUnitCase(
        DescribedByExtractorDummyEnumWithNonStringReturnType::class,
        'PascalCase'
    );

    DescribedByExtractor::extract($reflection);
})->throws(
    ValueError::class,
    sprintf(
        'The describer method `description` on `%s` must return a string',
        DescribedByExtractorDummyEnumWithNonStringReturnType::class
    )
);
