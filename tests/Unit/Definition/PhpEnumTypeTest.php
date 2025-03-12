<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Definition;

use GraphQL\Type\Definition\Description;
use GraphQL\Type\Definition\EnumValueDefinition;
use Illuminate\Support\Collection;
use Worksome\GraphQLHelpers\Definition\PhpEnumType;

#[Description('Dummy enum description')]
enum DummyEnum
{
    #[Description('PascalCase description')]
    case PascalCase;

    #[Description('SCREAMING_SNAKE_CASE description')]
    case SCREAMING_SNAKE_CASE; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case; // phpcs:ignore
}

#[Description('Dummy enum description')]
enum DummyStringEnum: string
{
    #[Description('PascalCase description')]
    case PascalCase = 'pascal-case';

    #[Description('SCREAMING_SNAKE_CASE description')]
    case SCREAMING_SNAKE_CASE = 'screaming-snake-case'; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case = 'snake-case'; // phpcs:ignore
}

#[Description('Dummy enum description')]
enum DummyIntEnum: int
{
    #[Description('PascalCase description')]
    case PascalCase = 1;

    #[Description('SCREAMING_SNAKE_CASE description')]
    case SCREAMING_SNAKE_CASE = 2; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case = 3; // phpcs:ignore
}

dataset(
    'dummy-enums',
    [
        DummyEnum::class,
        DummyIntEnum::class,
        DummyStringEnum::class,
    ],
);
it(
    'generates correct GQL name from enum cases',
    function ($enumClass) {
        $type = new PhpEnumType($enumClass);

        expect($type->name)->toBe(class_basename($enumClass));

        $names = Collection::make($type->getValues())
                           ->map(fn (EnumValueDefinition $definition) => $definition->name)
                           ->all();

        expect($names)->toBe(
            [
                'PASCAL_CASE',
                'SCREAMING_SNAKE_CASE',
                'SNAKE_CASE',
            ],
        );
    },
)->with('dummy-enums');

it(
    'generates correct GQL value from enum cases',
    function ($enumClass) {
        $type = new PhpEnumType($enumClass);

        $values = Collection::make($type->getValues())
                            ->map(fn (EnumValueDefinition $definition) => $definition->value)
                            ->all();

        expect($values)->toBe(
            [
                $enumClass::PascalCase,
                $enumClass::SCREAMING_SNAKE_CASE,
                $enumClass::snake_case,
            ],
        );
    },
)->with('dummy-enums');

it(
    'extracts description from php attribute',
    function ($enumClass) {
        $type = new PhpEnumType($enumClass);

        $descriptions = Collection::make($type->getValues())->map(
            fn (EnumValueDefinition $definition) => $definition->description,
        )->all();

        expect($type->description)->toBe('Dummy enum description')->and($descriptions)->toBe(
            [
                'PascalCase description',
                'SCREAMING_SNAKE_CASE description',
                'snake_case description',
            ],
        );
    },
)->with('dummy-enums');
