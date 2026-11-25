<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition\Attributes;

use Attribute;

/**
 * Overrides the GraphQL value name of an enum case, which is otherwise the case name converted to UPPER_SNAKE_CASE.
 *
 * Use it to keep an existing GraphQL value that no PascalCase case name converts to, such as `CONTRACT_TYPE_W_2`.
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
readonly class GraphQLName
{
    public function __construct(public string $name)
    {
    }
}
