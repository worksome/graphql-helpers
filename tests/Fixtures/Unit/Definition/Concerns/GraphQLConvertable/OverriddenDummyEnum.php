<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\Unit\Definition\Concerns\GraphQLConvertable;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;
use Worksome\GraphQLHelpers\Definition\Concerns\GraphQLConvertable;

enum OverriddenDummyEnum
{
    use GraphQLConvertable;

    #[GraphQLName('CONTRACT_TYPE_W_2')]
    case ContractTypeW2;

    case ContractTypeDefault;
}
