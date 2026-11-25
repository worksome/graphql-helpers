<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum NamedArgumentOverrides
{
    #[GraphQLName(name: 'SAME')]
    case First;

    #[GraphQLName(name: 'SAME')]
    case Second;
}
