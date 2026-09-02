<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition;

use GraphQL\Type\Definition\Deprecated;

enum EnumWithDeprecatedCases
{
    #[Deprecated('This is deprecated.')]
    case Deprecated;

    case NotDeprecated;
}
