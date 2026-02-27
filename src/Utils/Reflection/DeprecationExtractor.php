<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Utils\Reflection;

use GraphQL\Type\Definition\Deprecated;
use LogicException;
use ReflectionEnumUnitCase;

class DeprecationExtractor
{
    public const string MULTIPLE_DEPRECATIONS_DISALLOWED = 'Using more than 1 Deprecated attribute is not supported.';

    public static function extract(ReflectionEnumUnitCase $reflection): string|null
    {
        $deprecations = $reflection->getAttributes(Deprecated::class);

        if (count($deprecations) === 0) {
            return null;
        }

        if (count($deprecations) > 1) {
            throw new LogicException(self::MULTIPLE_DEPRECATIONS_DISALLOWED);
        }

        return $deprecations[0]->newInstance()->reason;
    }
}
