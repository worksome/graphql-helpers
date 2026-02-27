<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Utils\Reflection;

use GraphQL\Type\Definition\Deprecated;
use ReflectionEnumUnitCase;

class DeprecationExtractor
{
    public static function extract(ReflectionEnumUnitCase $reflection): string|null
    {
        $deprecations = $reflection->getAttributes(Deprecated::class);

        if (count($deprecations) === 1) {
            return $deprecations[0]->newInstance()->reason;
        }

        return null;
    }
}
