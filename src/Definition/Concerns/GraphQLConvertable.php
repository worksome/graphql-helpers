<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition\Concerns;

use ReflectionEnumUnitCase;
use UnitEnum;
use Worksome\GraphQLHelpers\Utils\PascalCaseConverter;
use Worksome\GraphQLHelpers\Utils\Reflection\GraphQLNameExtractor;

/**
 * @mixin UnitEnum
 *
 * @phpstan-ignore trait.unused
 */
trait GraphQLConvertable
{
    public function toGraphQLValue(): string
    {
        return GraphQLNameExtractor::forCase($this);
    }

    public static function tryFromGraphQLValue(string $value): self|null
    {
        $case = array_find(self::cases(), fn (self $case) => GraphQLNameExtractor::forCase($case) === $value);

        if ($case !== null) {
            return $case;
        }

        // Fall back to the case name, which also accepts values that only differ in formatting. Cases with a
        // `GraphQLName` override only answer to that name.
        $name = PascalCaseConverter::convert($value);

        return array_find(
            self::cases(),
            fn (self $case) => $case->name === $name
                && GraphQLNameExtractor::extract(new ReflectionEnumUnitCase($case, $case->name)) === null,
        );
    }
}
