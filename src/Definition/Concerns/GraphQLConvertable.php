<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition\Concerns;

use Jawira\CaseConverter\Convert;
use UnitEnum;

/**
 * @mixin UnitEnum
 */
trait GraphQLConvertable
{
    public function toGraphQLValue(): string
    {
        return (new Convert($this->name))->toMacro();
    }
}
