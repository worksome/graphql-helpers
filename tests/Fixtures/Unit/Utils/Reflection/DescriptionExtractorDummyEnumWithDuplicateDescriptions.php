<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Utils\Reflection;

use GraphQL\Type\Definition\Description;

#[Description('One')]
#[Description('Two')]
enum DescriptionExtractorDummyEnumWithDuplicateDescriptions
{
    #[Description('One')]
    #[Description('Two')]
    case PascalCase;
}
