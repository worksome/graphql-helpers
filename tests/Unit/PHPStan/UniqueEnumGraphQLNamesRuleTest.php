<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\PHPStan;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Worksome\GraphQLHelpers\PHPStan\UniqueEnumGraphQLNamesRule;

/** @extends RuleTestCase<UniqueEnumGraphQLNamesRule> */
class UniqueEnumGraphQLNamesRuleTest extends RuleTestCase
{
    private const string FIXTURES = __DIR__ . '/../../Fixtures/PHPStan/';

    private const string NAMESPACE = 'Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan\\';

    public function test_it_allows_overrides_that_keep_every_name_unique(): void
    {
        $this->analyse([self::FIXTURES . 'ValidGraphQLNames.php'], []);
    }

    public function test_it_reports_two_overrides_with_the_same_name(): void
    {
        $this->analyse([self::FIXTURES . 'TwoOverridesWithTheSameName.php'], [
            [
                'Enum case ' . self::NAMESPACE . 'TwoOverridesWithTheSameName::Second has the GraphQL name [SAME], which case First already uses.',
                15,
            ],
        ]);
    }

    public function test_it_reports_an_override_matching_another_cases_converted_name(): void
    {
        $this->analyse([self::FIXTURES . 'OverrideMatchingAConvertedName.php'], [
            [
                'Enum case ' . self::NAMESPACE . 'OverrideMatchingAConvertedName::Override has the GraphQL name [PLAIN_NAME], which case PlainName already uses.',
                14,
            ],
        ]);
    }

    public function test_it_reports_case_names_that_convert_to_the_same_name(): void
    {
        $this->analyse([self::FIXTURES . 'ConvertedNamesThatCollide.php'], [
            [
                'Enum case ' . self::NAMESPACE . 'ConvertedNamesThatCollide::snake_case has the GraphQL name [SNAKE_CASE], which case SnakeCase already uses.',
                10,
            ],
        ]);
    }

    public function test_it_reports_a_converted_name_matching_an_earlier_override(): void
    {
        $this->analyse([self::FIXTURES . 'OverrideBeforeMatchingConvertedName.php'], [
            [
                'Enum case ' . self::NAMESPACE . 'OverrideBeforeMatchingConvertedName::PlainName has the GraphQL name [PLAIN_NAME], which case Override already uses.',
                14,
            ],
        ]);
    }

    public function test_it_reports_every_later_case_against_the_first_case_using_the_name(): void
    {
        $this->analyse([self::FIXTURES . 'ThreeCasesWithTheSameName.php'], [
            [
                'Enum case ' . self::NAMESPACE . 'ThreeCasesWithTheSameName::Second has the GraphQL name [SAME], which case Same already uses.',
                14,
            ],
            [
                'Enum case ' . self::NAMESPACE . 'ThreeCasesWithTheSameName::Third has the GraphQL name [SAME], which case Same already uses.',
                17,
            ],
        ]);
    }

    public function test_it_reads_overrides_given_as_a_named_argument(): void
    {
        $this->analyse([self::FIXTURES . 'NamedArgumentOverrides.php'], [
            [
                'Enum case ' . self::NAMESPACE . 'NamedArgumentOverrides::Second has the GraphQL name [SAME], which case First already uses.',
                15,
            ],
        ]);
    }

    protected function getRule(): Rule
    {
        return new UniqueEnumGraphQLNamesRule();
    }
}
