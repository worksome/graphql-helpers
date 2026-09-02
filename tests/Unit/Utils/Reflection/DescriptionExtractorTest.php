<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Utils\Reflection;

use LogicException;
use ReflectionEnum;
use ReflectionEnumUnitCase;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DescriptionExtractorDummyEnum;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DescriptionExtractorDummyEnumWithDescribedBy;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DescriptionExtractorDummyEnumWithDocBlock;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection\DescriptionExtractorDummyEnumWithDuplicateDescriptions;
use Worksome\GraphQLHelpers\Utils\Reflection\DescriptionExtractor;

it('can extract the description from an enum', function () {
    $reflection = new ReflectionEnum(DescriptionExtractorDummyEnum::class);

    expect(DescriptionExtractor::extract($reflection))->toBe('Dummy enum description');
});

it(
    'can extract the description from an enum case',
    function (DescriptionExtractorDummyEnum $enum, string|null $description) {
        $reflection = new ReflectionEnumUnitCase($enum, $enum->name);

        expect(DescriptionExtractor::extract($reflection))->toBe($description);
    }
)->with([
    [DescriptionExtractorDummyEnum::PascalCase, 'PascalCase description'],
    [DescriptionExtractorDummyEnum::SCREAMING_SNAKE_CASE, 'SCREAMING_SNAKE_CASE description'],
    [DescriptionExtractorDummyEnum::snake_case, 'snake_case description'],
    [DescriptionExtractorDummyEnum::NoDescription, null],
]);

it('does not extract the description from an enum doc block', function () {
    $reflection = new ReflectionEnum(DescriptionExtractorDummyEnumWithDocBlock::class);

    expect(DescriptionExtractor::extract($reflection))->toBeNull();
});

it('does not extract the description from an enum case doc block', function () {
    $reflection = new ReflectionEnumUnitCase(DescriptionExtractorDummyEnum::class, 'DocBlockOnly');

    expect(DescriptionExtractor::extract($reflection))->toBeNull();
});

it('does not resolve the description from a describer', function () {
    $reflection = new ReflectionEnumUnitCase(DescriptionExtractorDummyEnumWithDescribedBy::class, 'PascalCase');

    expect(DescriptionExtractor::extract($reflection))->toBeNull();
});

it('throws when an enum has more than one description attribute', function () {
    $reflection = new ReflectionEnum(DescriptionExtractorDummyEnumWithDuplicateDescriptions::class);

    DescriptionExtractor::extract($reflection);
})->throws(LogicException::class, DescriptionExtractor::MULTIPLE_DESCRIPTIONS_DISALLOWED);

it('throws when an enum case has more than one description attribute', function () {
    $reflection = new ReflectionEnumUnitCase(
        DescriptionExtractorDummyEnumWithDuplicateDescriptions::class,
        'PascalCase'
    );

    DescriptionExtractor::extract($reflection);
})->throws(LogicException::class, DescriptionExtractor::MULTIPLE_DESCRIPTIONS_DISALLOWED);
