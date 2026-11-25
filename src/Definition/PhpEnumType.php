<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition;

use GraphQL\Error\SerializationError;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Utils\Utils;
use LogicException;
use ReflectionEnum;
use ReflectionEnumUnitCase;
use UnitEnum;
use Worksome\GraphQLHelpers\Utils\Reflection\DeprecationExtractor;
use Worksome\GraphQLHelpers\Utils\Reflection\DescribedByExtractor;
use Worksome\GraphQLHelpers\Utils\Reflection\DescriptionExtractor;
use Worksome\GraphQLHelpers\Utils\Reflection\GraphQLNameExtractor;
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
            $graphQLName = $this->caseName($case);

            // An override must not replace another case's value. Collisions between plain case names are left as
            // they were (the later case wins) so existing enums keep working.
            if (array_key_exists($graphQLName, $enumDefinitions) && $this->hasOverride($graphQLName, $reflection)) {
                throw new LogicException(
                    "Enum {$this->enumClass} has more than one case with the GraphQL name [{$graphQLName}]."
                );
            }

            $enumDefinitions[$graphQLName] = [
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

        return GraphQLNameExtractor::forCase($value);
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

    /** @param  ReflectionEnum<UnitEnum>  $reflection */
    protected function hasOverride(string $graphQLName, ReflectionEnum $reflection): bool
    {
        foreach ($reflection->getCases() as $case) {
            if (GraphQLNameExtractor::extract($case) === $graphQLName) {
                return true;
            }
        }

        return false;
    }

    protected function caseName(ReflectionEnumUnitCase $case): string
    {
        return GraphQLNameExtractor::extract($case) ?? UpperSnakeCaseConverter::convert($case->name);
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
