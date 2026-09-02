<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection;

use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;
use Worksome\GraphQLHelpers\Definition\Concerns\GraphQLDescribable;

#[CasesDescribedBy(describer: 'description')]
enum DescribedByExtractorDummyEnumUsingDescribableConcern
{
    use GraphQLDescribable;

    case PascalCase;
}
