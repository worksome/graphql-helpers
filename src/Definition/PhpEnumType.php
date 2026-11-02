<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition;

use GraphQL\Error\SerializationError;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Utils\Utils;
use ReflectionEnum;
use ReflectionEnumUnitCase;
use UnitEnum;
use Worksome\GraphQLHelpers\Utils\Reflection\DeprecationExtractor;
use Worksome\GraphQLHelpers\Utils\Reflection\DescribedByExtractor;
use Worksome\GraphQLHelpers\Utils\Reflection\DescriptionExtractor;
use Worksome\GraphQLHelpers\Utils\UpperSnakeCaseConverter;

/** @phpstan-import-type PartialEnumValueConfig from EnumType */
class PhpEnumType extends EnumType
{
    /** @param  class-string<UnitEnum>  $enumClass */
    public function __construct(protected string $enumClass, string|null $name = null, string|null $description = null)
    {
        $reflection = new ReflectionEnum($this->enumClass);

        /** @var array<string, PartialEnumValueConfig> $enumDefinitions */
        $enumDefinitions = [];
        foreach ($reflection->getCases() as $case) {
            $enumDefinitions[UpperSnakeCaseConverter::convert($case->name)] = [
                'value' => $case->getValue(),
                'description' => $this->caseDescription($case),
                'deprecationReason' => $this->caseDeprecationReason($case),
            ];
        }

        parent::__construct(
            [
                'name' => $name ?? $this->baseName($this->enumClass),
                'values' => $enumDefinitions,
                'description' => $description ?? $this->enumDescription($reflection),
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

    /** @param  ReflectionEnum<UnitEnum>  $reflection */
    protected function enumDescription(ReflectionEnum $reflection): string|null
    {
        return DescriptionExtractor::extract($reflection);
    }

    protected function caseDescription(ReflectionEnumUnitCase $case): string|null
    {
        return DescriptionExtractor::extract($case) ?? DescribedByExtractor::extract($case);
    }

    protected function caseDeprecationReason(ReflectionEnumUnitCase $case): string|null
    {
        return DeprecationExtractor::extract($case);
    }

    /** @param  class-string  $class */
    protected function baseName(string $class): string
    {
        $parts = explode('\\', $class);

        return end($parts);
    }
}
