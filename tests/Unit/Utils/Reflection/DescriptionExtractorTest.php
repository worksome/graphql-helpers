<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Utils\Reflection;

use GraphQL\Type\Definition\Description;
use LogicException;
use ReflectionEnum;
use ReflectionEnumUnitCase;
use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;
use Worksome\GraphQLHelpers\Utils\Reflection\DescriptionExtractor;

#[Description('Dummy enum description')]
enum DescriptionExtractorDummyEnum
{
    #[Description('PascalCase description')]
    case PascalCase;

    #[Description('SCREAMING_SNAKE_CASE description')]
    case SCREAMING_SNAKE_CASE; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case; // phpcs:ignore

    case NoDescription;

    /** This doc block should be ignored */
    case DocBlockOnly;
}

/** This doc block should be ignored */
enum DescriptionExtractorDummyEnumWithDocBlock
{
    case PascalCase;
}

#[CasesDescribedBy(describer: 'description')]
enum DescriptionExtractorDummyEnumWithDescribedBy
{
    case PascalCase;

    public function description(): string
    {
        return 'Description from the describer';
    }
}

#[Description('One')]
#[Description('Two')]
enum DescriptionExtractorDummyEnumWithDuplicateDescriptions
{
    #[Description('One')]
    #[Description('Two')]
    case PascalCase;
}

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
