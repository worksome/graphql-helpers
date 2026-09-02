<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection;

use GraphQL\Type\Definition\Deprecated;

enum DeprecationExtractorDummyEnum
{
    #[Deprecated('This is deprecated.')]
    case Deprecated;

    case NotDeprecated;

    #[Deprecated('One')]
    #[Deprecated('Two')]
    case DeprecatedTwice;
}
