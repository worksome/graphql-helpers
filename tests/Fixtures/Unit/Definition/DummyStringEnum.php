<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition;

use GraphQL\Type\Definition\Description;

#[Description('Dummy enum description')]
enum DummyStringEnum: string
{
    #[Description('PascalCase description')]
    case PascalCase = 'pascal-case';

    #[Description('SCREAMING_SNAKE_CASE description')]
    case SCREAMING_SNAKE_CASE = 'screaming-snake-case'; // phpcs:ignore

    #[Description('snake_case description')]
    case snake_case = 'snake-case'; // phpcs:ignore
}
