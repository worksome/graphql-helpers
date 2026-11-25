<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum OverrideMatchingAConvertedName
{
    case PlainName;

    #[GraphQLName('PLAIN_NAME')]
    case Override;
}
