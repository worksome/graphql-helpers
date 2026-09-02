<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition;

use GraphQL\Type\Definition\Description;
use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

#[CasesDescribedBy(describer: 'description')]
enum EnumWithDescriptionAttributeAndMethod
{
    #[Description('Description from the attribute')]
    case Main;

    public function description(): string
    {
        return 'Description from the describer';
    }
}
