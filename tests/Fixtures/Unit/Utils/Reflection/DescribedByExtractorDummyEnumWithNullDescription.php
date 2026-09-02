<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection;

use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

#[CasesDescribedBy(describer: 'description')]
enum DescribedByExtractorDummyEnumWithNullDescription
{
    case PascalCase;

    public function description(): string|null
    {
        return null;
    }
}
