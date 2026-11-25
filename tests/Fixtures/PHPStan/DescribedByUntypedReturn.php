<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

#[CasesDescribedBy('description')]
enum DescribedByUntypedReturn
{
    case First;

    public function description() // phpcs:ignore
    {
        return $this->name;
    }
}
