<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition\Concerns;

use Jawira\CaseConverter\Convert;

/**
 * @property-read string $name
 */
trait GraphQLConvertable
{
    public function graphQLValue(): string
    {
        return (new Convert($this->name))->toMacro();
    }
}
