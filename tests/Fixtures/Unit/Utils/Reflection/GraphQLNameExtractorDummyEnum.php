<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum GraphQLNameExtractorDummyEnum
{
    #[GraphQLName('CUSTOM_NAME')]
    case Overridden;

    case NotOverridden;

    #[GraphQLName('ONE')]
    #[GraphQLName('TWO')]
    case OverriddenTwice;
}
