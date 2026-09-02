<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Utils\Reflection;

use GraphQL\Type\Definition\Description;
use LogicException;
use ReflectionEnum;
use ReflectionEnumUnitCase;
use UnitEnum;

class DescriptionExtractor
{
    public const string MULTIPLE_DESCRIPTIONS_DISALLOWED = 'Using more than 1 Description attribute is not supported.';

    /** @param  ReflectionEnum<UnitEnum>|ReflectionEnumUnitCase  $reflection */
    public static function extract(
        ReflectionEnum|ReflectionEnumUnitCase $reflection,
    ): string|null {
        $descriptions = $reflection->getAttributes(Description::class);

        if (count($descriptions) === 0) {
            return null;
        }

        if (count($descriptions) > 1) {
            throw new LogicException(self::MULTIPLE_DESCRIPTIONS_DISALLOWED);
        }

        return $descriptions[0]->newInstance()->description;
    }
}
