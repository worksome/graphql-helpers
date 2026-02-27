<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition;

use GraphQL\Error\SerializationError;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Utils\Utils;
use ReflectionEnum;
use UnitEnum;
use Worksome\GraphQLHelpers\Utils\Reflection\DeprecationExtractor;
use Worksome\GraphQLHelpers\Utils\Reflection\DescriptionExtractor;
use Worksome\GraphQLHelpers\Utils\UpperSnakeCaseConverter;

/** @phpstan-import-type PartialEnumValueConfig from EnumType */
class PhpEnumType extends EnumType
{
    /** @var class-string<UnitEnum> */
    protected string $enumClass;

    /** @param  class-string<UnitEnum>  $enumClass */
    public function __construct(string $enumClass, string|null $name = null)
    {
        $this->enumClass = $enumClass;
        $reflection = new ReflectionEnum($enumClass);

        /** @var array<string, PartialEnumValueConfig> $enumDefinitions */
        $enumDefinitions = [];
        foreach ($reflection->getCases() as $case) {
            $enumDefinitions[UpperSnakeCaseConverter::convert($case->name)] = [
                'value' => $case->getValue(),
                'description' => DescriptionExtractor::extract($case),
                'deprecationReason' => DeprecationExtractor::extract($case),
            ];
        }

        parent::__construct(
            [
                'name' => $name ?? $this->baseName($enumClass),
                'values' => $enumDefinitions,
                'description' => DescriptionExtractor::extract($reflection),
            ]
        );
    }

    public function serialize($value): string
    {
        if (! ($value instanceof $this->enumClass)) {
            $notEnum = Utils::printSafe($value);

            throw new SerializationError(
                "Cannot serialize value as enum: {$notEnum}, expected instance of {$this->enumClass}."
            );
        }

        return UpperSnakeCaseConverter::convert($value->name);
    }

    public function parseValue($value)
    {
        // Can happen when variable values undergo a serialization cycle before execution
        if ($value instanceof $this->enumClass) {
            return $value;
        }

        return parent::parseValue($value);
    }

    /** @param  class-string  $class */
    protected function baseName(string $class): string
    {
        $parts = explode('\\', $class);

        return end($parts);
    }
}
