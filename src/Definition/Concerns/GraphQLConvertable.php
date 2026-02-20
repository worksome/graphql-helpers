<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition\Concerns;

use UnitEnum;
use Worksome\GraphQLHelpers\Utils\PascalCaseConverter;
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

    public static function tryFromGraphQLValue(string $value): self|null
    {
        $name = PascalCaseConverter::convert($value);

        return array_find(self::cases(), fn (self $case) => $case->name === $name);
    }
}
