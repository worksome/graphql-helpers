<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum ValidGraphQLNameOverrides
{
    #[GraphQLName('CONTRACT_TYPE_W_2')]
    case W2;

    #[GraphQLName(name: 'NAMED_ARGUMENT')]
    case NamedArgument;

    #[GraphQLName('_LEADING_UNDERSCORE')]
    case LeadingUnderscore;

    case WithoutOverride;
}
