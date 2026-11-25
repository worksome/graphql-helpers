<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum NonLiteralGraphQLName
{
    private const string NAME = 'FROM_CONSTANT';

    #[GraphQLName(self::NAME)]
    case FromConstant;
}
