<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition\Concerns;

use Exception;
use GraphQL\Type\Definition\Description;
use Illuminate\Support\Str;
use ReflectionEnum;
use UnitEnum;

/** @mixin UnitEnum */
trait GraphQLDescribable
{
    public function description(): string
    {
        $reflection = new ReflectionEnum(static::class);
        $constReflection = $reflection->getCase($this->name);

        $descriptionAttributes = $constReflection->getAttributes(Description::class);

        return match (count($descriptionAttributes)) {
            0 => self::friendlyDescription($this->name),
            1 => $descriptionAttributes[0]->newInstance()->description,
            default => throw new Exception(
                'You cannot use more than 1 description attribute on ' . class_basename(static::class) . '::' . $this->name
            ),
        };
    }

    private function friendlyDescription(string $name): string
    {
        if (ctype_upper(preg_replace('/[^a-zA-Z]/', '', $name))) {
            $name = strtolower($name);
        }

        return ucfirst(str_replace('_', ' ', Str::snake($name)));
    }
}
