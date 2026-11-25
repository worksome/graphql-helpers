<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

#[CasesDescribedBy(self::DESCRIBER)]
enum DescribedByNonLiteral
{
    private const string DESCRIBER = 'description';

    case First;

    public function description(): string
    {
        return $this->name;
    }
}
