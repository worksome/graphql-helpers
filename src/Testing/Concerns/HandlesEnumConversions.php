<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Testing\Concerns;

use UnitEnum;
use Worksome\GraphQLHelpers\Utils\Reflection\GraphQLNameExtractor;

trait HandlesEnumConversions
{
    public function enumToGraphQL(UnitEnum $enum): string
    {
        return GraphQLNameExtractor::forCase($enum);
    }
}
