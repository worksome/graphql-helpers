<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Definition\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class CasesDescribedBy
{
    public function __construct(public string $describer)
    {
    }
}
