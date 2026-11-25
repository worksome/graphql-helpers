<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

#[CasesDescribedBy('description')]
enum DescribedByValidMethod
{
    case First;

    public function description(string $prefix = ''): string|null
    {
        return $prefix . $this->name;
    }
}
