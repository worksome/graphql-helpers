<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Utils\Reflection;

use GraphQL\Type\Definition\Description;
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

it('can extract the description from an enum case with a describer', function () {
    $reflection = new ReflectionEnumUnitCase(DescriptionExtractorDummyEnumWithDescribedBy::class, 'PascalCase');

    expect(DescriptionExtractor::extract($reflection))->toBe('Description from the describer');
});
