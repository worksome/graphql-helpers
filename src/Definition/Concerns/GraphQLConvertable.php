<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition\Concerns;

use UnitEnum;
use Worksome\GraphQLHelpers\Utils\UpperSnakeCaseConverter;

/**
 * @mixin UnitEnum
 *
 * @phpstan-ignore trait.unused
 */
trait GraphQLConvertable
{
    public function toGraphQLValue(): string
    {
        return UpperSnakeCaseConverter::convert($this->name);
    }
}
