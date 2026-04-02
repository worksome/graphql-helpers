<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Utils\Reflection;

use GraphQL\Type\Definition\Description;
use GraphQL\Utils\PhpDoc;
use ReflectionEnum;
use ReflectionEnumUnitCase;

class DescriptionExtractor
{
    /** @phpstan-ignore-next-line */
    public static function extract(
        ReflectionEnum|ReflectionEnumUnitCase $reflection,
    ): string|null {
        $descriptions = $reflection->getAttributes(Description::class);

        if (count($descriptions) === 1) {
            return $descriptions[0]->newInstance()->description;
        }

        if (
            $reflection instanceof ReflectionEnumUnitCase
            && $description = DescribedByExtractor::extract($reflection)
        ) {
            return $description;
        }

        $comment = $reflection->getDocComment();
        $unpadded = PhpDoc::unpad($comment);

        return PhpDoc::unwrap($unpadded);
    }
}
