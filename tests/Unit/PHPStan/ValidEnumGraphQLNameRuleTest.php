<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\PHPStan;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Worksome\GraphQLHelpers\PHPStan\ValidEnumGraphQLNameRule;

/** @extends RuleTestCase<ValidEnumGraphQLNameRule> */
class ValidEnumGraphQLNameRuleTest extends RuleTestCase
{
    private const string FIXTURES = __DIR__ . '/../../Fixtures/PHPStan/';

    public function test_it_allows_valid_names_given_positionally_or_by_name(): void
    {
        $this->analyse([self::FIXTURES . 'ValidGraphQLNameOverrides.php'], []);
    }

    public function test_it_reports_names_that_do_not_match_the_graphql_name_pattern(): void
    {
        $this->analyse([self::FIXTURES . 'InvalidGraphQLNameCharacters.php'], [
            [
                'The #[GraphQLName] on enum case Hyphen is not a valid GraphQL enum value name: Names must match /^[_a-zA-Z][_a-zA-Z0-9]*$/ but "W-2" does not.',
                11,
            ],
            [
                'The #[GraphQLName] on enum case LeadingDigit is not a valid GraphQL enum value name: Names must match /^[_a-zA-Z][_a-zA-Z0-9]*$/ but "2ND" does not.',
                14,
            ],
        ]);
    }

    public function test_it_reports_names_using_the_reserved_introspection_prefix(): void
    {
        $this->analyse([self::FIXTURES . 'ReservedGraphQLNamePrefix.php'], [
            [
                'The #[GraphQLName] on enum case Introspection is not a valid GraphQL enum value name: Name "__TYPE" must not begin with "__", which is reserved by GraphQL introspection.',
                11,
            ],
        ]);
    }

    public function test_it_reports_names_reserved_as_enum_values(): void
    {
        $this->analyse([self::FIXTURES . 'ReservedGraphQLNameValue.php'], [
            [
                'The #[GraphQLName] on enum case Nothing is not a valid GraphQL enum value name: "null" is reserved and cannot be used as an enum value.',
                11,
            ],
        ]);
    }

    public function test_it_reports_names_that_are_not_literal_strings(): void
    {
        $this->analyse([self::FIXTURES . 'NonLiteralGraphQLName.php'], [
            [
                'The #[GraphQLName] on enum case FromConstant must be given a literal string, so it can be checked.',
                13,
            ],
        ]);
    }

    protected function getRule(): Rule
    {
        return new ValidEnumGraphQLNameRule();
    }
}
