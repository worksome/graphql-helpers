<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Utils\Reflection;

use ReflectionEnumUnitCase;
use ValueError;
use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

class DescribedByExtractor
{
    public static function extract(ReflectionEnumUnitCase $reflection): string|null
    {
        $enum = $reflection->getEnum();

        $describers = $enum->getAttributes(CasesDescribedBy::class);

        if (count($describers) === 0) {
            return null;
        }

        $describer = $describers[0]->newInstance()->describer;

        if (! $enum->hasMethod($describer)) {
            throw new ValueError(
                sprintf(
                    'The describer method `%s` does not exist on `%s`',
                    $describer,
                    $enum->name
                )
            );
        }

        $description = $enum->getMethod($describer)->invoke($reflection->getValue());

        if ($description === null) {
            return null;
        }

        if (! is_string($description)) {
            throw new ValueError(
                sprintf(
                    'The describer method `%s` on `%s` must return a string',
                    $describer,
                    $enum->name
                )
            );
        }

        return $description;
    }
}
