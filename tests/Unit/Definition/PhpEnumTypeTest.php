<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\Definition;

use GraphQL\Error\Error;
use GraphQL\Error\SerializationError;
use GraphQL\Type\Definition\EnumValueDefinition;
use Illuminate\Support\Collection;
use Worksome\GraphQLHelpers\Definition\PhpEnumType;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\DummyEnum;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\DummyIntEnum;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\DummyStringEnum;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\EnumWithDeprecatedCases;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\EnumWithDescriptionAttributeAndMethod;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\EnumWithDescriptionMethod;
use Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\EnumWithDocBlocks;

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

it('extracts description from method', function () {
    $type = new PhpEnumType(EnumWithDescriptionMethod::class);

    $descriptions = Collection::make($type->getValues())->map(
        fn (EnumValueDefinition $definition) => $definition->description,
    )->all();

    expect($descriptions)->toBe([
        'Main enum description',
    ]);
});

it('prefers the description attribute over the describer method', function () {
    $type = new PhpEnumType(EnumWithDescriptionAttributeAndMethod::class);

    expect($type->getValue('MAIN')->description)->toBe('Description from the attribute');
});

it('does not extract descriptions from doc blocks', function () {
    $type = new PhpEnumType(EnumWithDocBlocks::class);

    expect($type->description)->toBeNull()
        ->and($type->getValue('MAIN')->description)->toBeNull();
});

it('uses the provided name instead of the enum base name', function () {
    $type = new PhpEnumType(DummyEnum::class, 'Renamed');

    expect($type->name)->toBe('Renamed');
});

it('extracts the deprecation reason from php attribute', function () {
    $type = new PhpEnumType(EnumWithDeprecatedCases::class);

    expect($type->getValue('DEPRECATED')->deprecationReason)->toBe('This is deprecated.')
        ->and($type->getValue('DEPRECATED')->isDeprecated())->toBeTrue()
        ->and($type->getValue('NOT_DEPRECATED')->deprecationReason)->toBeNull()
        ->and($type->getValue('NOT_DEPRECATED')->isDeprecated())->toBeFalse();
});

it(
    'serializes an enum case to its GQL name',
    function ($enumClass) {
        $type = new PhpEnumType($enumClass);

        expect($type->serialize($enumClass::PascalCase))->toBe('PASCAL_CASE')
            ->and($type->serialize($enumClass::SCREAMING_SNAKE_CASE))->toBe('SCREAMING_SNAKE_CASE')
            ->and($type->serialize($enumClass::snake_case))->toBe('SNAKE_CASE');
    },
)->with('dummy-enums');

it('throws when serializing a value that is not an instance of the enum', function ($value) {
    $type = new PhpEnumType(DummyEnum::class);

    $type->serialize($value);
})->with([
    'string' => ['PASCAL_CASE'],
    'null' => [null],
    'int' => [1],
    'other enum' => [DummyIntEnum::PascalCase],
])->throws(SerializationError::class);

it(
    'parses a GQL name to the matching enum case',
    function ($enumClass) {
        $type = new PhpEnumType($enumClass);

        expect($type->parseValue('PASCAL_CASE'))->toBe($enumClass::PascalCase)
            ->and($type->parseValue('SCREAMING_SNAKE_CASE'))->toBe($enumClass::SCREAMING_SNAKE_CASE)
            ->and($type->parseValue('SNAKE_CASE'))->toBe($enumClass::snake_case);
    },
)->with('dummy-enums');

it(
    'parses an enum case that has already been through a serialization cycle',
    function ($enumClass) {
        $type = new PhpEnumType($enumClass);

        expect($type->parseValue($enumClass::PascalCase))->toBe($enumClass::PascalCase);
    },
)->with('dummy-enums');

it('throws when parsing a value that does not exist in the enum', function () {
    $type = new PhpEnumType(DummyEnum::class);

    $type->parseValue('NON_EXISTENT');
})->throws(Error::class, 'Value "NON_EXISTENT" does not exist in "DummyEnum" enum.');
