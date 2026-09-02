<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\Concerns\GraphQLConvertable;

use GraphQL\Type\Definition\Description;
use Worksome\GraphQLHelpers\Definition\Concerns\GraphQLConvertable;

enum PascalOnlyDummyEnum: string
{
    use GraphQLConvertable;

    #[Description('PascalCase description')]
    case PascalCase = 'test';

    #[Description('UPPERCASE_NUMERIC description')]
    case IR35 = 'IR35';

    #[Description('numeric description')]
    case _123 = '123';
}
