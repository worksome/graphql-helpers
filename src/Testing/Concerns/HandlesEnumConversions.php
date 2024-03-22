<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Testing\Concerns;

use Jawira\CaseConverter\Convert;
use UnitEnum;

trait HandlesEnumConversions
{
    public function enumToGraphQL(UnitEnum $enum): string
    {
        return (new Convert($enum->name))->toMacro();
    }
}
