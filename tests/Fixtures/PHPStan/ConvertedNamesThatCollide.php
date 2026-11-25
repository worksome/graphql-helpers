<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

enum ConvertedNamesThatCollide
{
    case SnakeCase;
    case snake_case; // phpcs:ignore
}
