<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\Concerns\GraphQLDescribable;

use GraphQL\Type\Definition\Description;
use Worksome\GraphQLHelpers\Definition\Concerns\GraphQLDescribable;

#[Description('Dummy enum description')]
enum DummyEnum
{
    use GraphQLDescribable;

    #[Description('PascalCase description')]
    case PascalCase;

    #[Description('SCREAMING_SNAKE_CASE description')]
    case SCREAMING_SNAKE_CASE; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case; // phpcs:ignore

    case NoDescription;

    /** This doc block should be ignored */
    case DocBlockOnly;
}
