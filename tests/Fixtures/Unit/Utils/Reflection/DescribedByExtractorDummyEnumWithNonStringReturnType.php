<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection;

use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

#[CasesDescribedBy(describer: 'description')]
enum DescribedByExtractorDummyEnumWithNonStringReturnType
{
    case PascalCase;

    public function description(): int
    {
        return 123;
    }
}
