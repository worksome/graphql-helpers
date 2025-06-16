<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition;

use Exception;
use GraphQL\Error\SerializationError;
use GraphQL\Type\Definition\Deprecated;
use GraphQL\Type\Definition\Description;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Utils\PhpDoc;
use GraphQL\Utils\Utils;
use Jawira\CaseConverter\Convert;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionEnum;
use UnitEnum;

/** @phpstan-import-type PartialEnumValueConfig from EnumType */
class PhpEnumType extends EnumType
{
    public const string MULTIPLE_DESCRIPTIONS_DISALLOWED = 'Using more than 1 Description attribute is not supported.';

    public const string MULTIPLE_DEPRECATIONS_DISALLOWED = 'Using more than 1 Deprecated attribute is not supported.';

    /** @var class-string<UnitEnum> */
    protected string $enumClass;

    /** @param class-string<UnitEnum> $enumClass */
    public function __construct(string $enumClass, string|null $name = null)
    {
        $this->enumClass = $enumClass;
        $reflection = new ReflectionEnum($enumClass);
        /** @var array<string, PartialEnumValueConfig> $enumDefinitions */
        $enumDefinitions = [];
        foreach ($reflection->getCases() as $case) {
            $enumDefinitions[(new Convert($case->name))->fromAuto(false)->toMacro()] = [
                'value' => $case->getValue(),
                'description' => $this->extractDescription($case),
                'deprecationReason' => $this->deprecationReason($case),
            ];
        }

        parent::__construct(
            [
                'name' => $name ?? $this->baseName($enumClass),
                'values' => $enumDefinitions,
                'description' => $this->extractDescription($reflection),
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

        return (new Convert($value->name))->fromAuto(false)->toMacro();
    }

    public function parseValue($value)
    {
        // Can happen when variable values undergo a serialization cycle before execution
        if ($value instanceof $this->enumClass) {
            return $value;
        }

        return parent::parseValue($value);
    }

    /** @param class-string $class */
    protected function baseName(string $class): string
    {
        $parts = explode('\\', $class);

        return end($parts);
    }

    /** @phpstan-ignore-next-line */
    protected function extractDescription(ReflectionClassConstant|ReflectionClass $reflection): string|null
    {
        $attributes = $reflection->getAttributes(Description::class);

        if (count($attributes) === 1) {
            return $attributes[0]->newInstance()->description;
        }

        if (count($attributes) > 1) {
            throw new Exception(self::MULTIPLE_DESCRIPTIONS_DISALLOWED);
        }

        $comment = $reflection->getDocComment();
        $unpadded = PhpDoc::unpad($comment);

        return PhpDoc::unwrap($unpadded);
    }

    protected function deprecationReason(ReflectionClassConstant $reflection): string|null
    {
        $attributes = $reflection->getAttributes(Deprecated::class);

        if (count($attributes) === 1) {
            return $attributes[0]->newInstance()->reason;
        }

        if (count($attributes) > 1) {
            throw new Exception(self::MULTIPLE_DEPRECATIONS_DISALLOWED);
        }

        return null;
    }
}
