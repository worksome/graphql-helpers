<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Utils;

use Jawira\CaseConverter\Convert;

class PascalCaseConverter
{
    /**
     * Converts a name to PascalCase while preserving leading underscores.
     *
     * Leading underscores are significant in GraphQL enum values (e.g. _4SC)
     * but confuse the case converter, so they are stripped before conversion
     * and re-prepended afterwards.
     */
    public static function convert(string $name): string
    {
        $leadingUnderscores = '';
        $remainder = $name;

        if (preg_match('/^(_+)(.+)$/', $name, $matches) === 1) {
            $leadingUnderscores = $matches[1];
            $remainder = $matches[2];
        }

        return $leadingUnderscores . new Convert($remainder)->fromAuto()->toPascal();
    }
}
