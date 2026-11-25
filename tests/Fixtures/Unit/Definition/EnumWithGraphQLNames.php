<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum EnumWithGraphQLNames: string
{
    #[GraphQLName('CONTRACT_TYPE_W_2')]
    case ContractTypeW2 = 'W-2';

    case ContractTypeDefault = 'default';
}
