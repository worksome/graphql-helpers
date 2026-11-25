<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Utils\Reflection;

use ReflectionEnumUnitCase;
use UnitEnum;
use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;
use Worksome\GraphQLHelpers\Utils\UpperSnakeCaseConverter;

class GraphQLNameExtractor
{
    /** The name set by a `GraphQLName` attribute on the case, or null when it has none. */
    public static function extract(ReflectionEnumUnitCase $reflection): string|null
    {
        // `GraphQLName` is not repeatable, so PHP throws when instantiating a case that uses it more than once.
        return ($reflection->getAttributes(GraphQLName::class)[0] ?? null)?->newInstance()->name;
    }

    /** The GraphQL value name of a case: its `GraphQLName` override, or its name converted to UPPER_SNAKE_CASE. */
    public static function forCase(UnitEnum $case): string
    {
        return self::extract(new ReflectionEnumUnitCase($case, $case->name))
            ?? UpperSnakeCaseConverter::convert($case->name);
    }
}
