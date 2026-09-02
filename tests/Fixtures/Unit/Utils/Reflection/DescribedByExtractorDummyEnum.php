<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection;

use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

#[CasesDescribedBy(describer: 'description')]
enum DescribedByExtractorDummyEnum
{
    case PascalCase;

    public function description(): string
    {
        return 'Description from the describer';
    }
}
