<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition;

use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

#[CasesDescribedBy(describer: 'description')]
enum EnumWithDescriptionMethod
{
    case Main;

    public function description(): string
    {
        return 'Main enum description';
    }
}
