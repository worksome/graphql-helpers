<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum TwoOverridesWithTheSameName
{
    #[GraphQLName('SAME')]
    case First;

    #[GraphQLName('SAME')]
    case Second;
}
