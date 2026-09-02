<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition;

use GraphQL\Type\Definition\Description;

#[Description('Dummy enum description')]
enum DummyIntEnum: int
{
    #[Description('PascalCase description')]
    case PascalCase = 1;

    #[Description('SCREAMING_SNAKE_CASE description')]
    case SCREAMING_SNAKE_CASE = 2; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case = 3; // phpcs:ignore
}
