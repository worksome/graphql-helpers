<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

#[CasesDescribedBy('description')]
enum DescribedByWrongReturnType
{
    case First;

    public function description(): int
    {
        return 1;
    }
}
