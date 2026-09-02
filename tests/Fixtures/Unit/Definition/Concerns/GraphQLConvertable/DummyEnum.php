<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\Concerns\GraphQLConvertable;

use GraphQL\Type\Definition\Description;
use Worksome\GraphQLHelpers\Definition\Concerns\GraphQLConvertable;

#[Description('Dummy enum description')]
enum DummyEnum
{
    use GraphQLConvertable;

    #[Description('PascalCase description')]
    case PascalCase;

    #[Description('MACRO_CASE description')]
    case MACRO_CASE; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case; // phpcs:ignore

    #[Description('UPPERCASE_NUMERIC description')]
    case IR35; // phpcs:ignore

    #[Description('numeric description')]
    case _123; // phpcs:ignore
}
