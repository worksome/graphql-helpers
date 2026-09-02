<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Testing\Concerns;

use UnitEnum;
use Worksome\GraphQLHelpers\Utils\UpperSnakeCaseConverter;

trait HandlesEnumConversions
{
    public function enumToGraphQL(UnitEnum $enum): string
    {
        return UpperSnakeCaseConverter::convert($enum->name);
    }
}
