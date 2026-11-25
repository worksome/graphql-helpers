<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Testing\Concerns;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum DummyEnum
{
    case PascalCase;
    case SCREAMING_SNAKE_CASE; // phpcs:ignore
    case snake_case; // phpcs:ignore
    case IR35; // phpcs:ignore
    case _123; // phpcs:ignore

    #[GraphQLName('CUSTOM_NAME')]
    case Overridden;
}
