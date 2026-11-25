<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum InvalidGraphQLNameCharacters
{
    #[GraphQLName('W-2')]
    case Hyphen;

    #[GraphQLName('2ND')]
    case LeadingDigit;
}
