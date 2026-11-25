<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum ThreeCasesWithTheSameName
{
    case Same;

    #[GraphQLName('SAME')]
    case Second;

    #[GraphQLName('SAME')]
    case Third;
}
