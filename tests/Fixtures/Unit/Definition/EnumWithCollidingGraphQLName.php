<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum EnumWithCollidingGraphQLName
{
    case Foo;

    #[GraphQLName('FOO')]
    case Bar;
}
