<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition\Concerns;

use Illuminate\Support\Str;
use ReflectionEnumUnitCase;
use UnitEnum;
use Worksome\GraphQLHelpers\Utils\Reflection\DescriptionExtractor;

/**
 * @mixin UnitEnum
 *
 * @phpstan-ignore trait.unused
 */
trait GraphQLDescribable
{
    public function description(): string
    {
        $reflection = new ReflectionEnumUnitCase(static::class, $this->name);

        return DescriptionExtractor::extract($reflection) ?? self::friendlyDescription($this->name);
    }

    private function friendlyDescription(string $name): string
    {
        if (ctype_upper(preg_replace('/[^a-zA-Z]/', '', $name))) {
            $name = strtolower($name);
        }

        return ucfirst(str_replace('_', ' ', Str::snake($name)));
    }
}
