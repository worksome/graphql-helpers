<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan;

use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

enum ValidGraphQLNames: string
{
    #[GraphQLName('CONTRACT_TYPE_W_2')]
    case W2 = 'W-2';

    #[GraphQLName('CONTRACT_TYPE_DEFAULT')]
    case NotSet = 'default';

    case StatementOfWork = 'Statement of Work';
}
