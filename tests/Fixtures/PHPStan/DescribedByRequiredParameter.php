<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

#[CasesDescribedBy('description')]
enum DescribedByRequiredParameter
{
    case First;

    public function description(string $prefix): string
    {
        return $prefix . $this->name;
    }
}
