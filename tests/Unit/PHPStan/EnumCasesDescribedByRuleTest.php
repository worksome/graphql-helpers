<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\Tests\Unit\PHPStan;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Worksome\GraphQLHelpers\PHPStan\EnumCasesDescribedByRule;

/** @extends RuleTestCase<EnumCasesDescribedByRule> */
class EnumCasesDescribedByRuleTest extends RuleTestCase
{
    private const string FIXTURES = __DIR__ . '/../../Fixtures/PHPStan/';

    private const string NAMESPACE = 'Worksome\GraphQLHelpers\Tests\Fixtures\PHPStan\\';

    public function test_it_allows_a_describer_returning_a_nullable_string_with_optional_parameters(): void
    {
        $this->analyse([self::FIXTURES . 'DescribedByValidMethod.php'], []);
    }

    public function test_it_reports_a_describer_method_that_does_not_exist(): void
    {
        $this->analyse([self::FIXTURES . 'DescribedByMissingMethod.php'], [
            [
                'The describer method description() named by #[CasesDescribedBy] does not exist on enum ' . self::NAMESPACE . 'DescribedByMissingMethod.',
                9,
            ],
        ]);
    }

    public function test_it_reports_a_describer_that_does_not_return_a_string(): void
    {
        $this->analyse([self::FIXTURES . 'DescribedByWrongReturnType.php'], [
            [
                'The describer method ' . self::NAMESPACE . 'DescribedByWrongReturnType::description() must return string or null, int given.',
                9,
            ],
        ]);
    }

    public function test_it_reports_a_describer_without_a_return_type(): void
    {
        $this->analyse([self::FIXTURES . 'DescribedByUntypedReturn.php'], [
            [
                'The describer method ' . self::NAMESPACE . 'DescribedByUntypedReturn::description() must return string or null, mixed given.',
                9,
            ],
        ]);
    }

    public function test_it_reports_a_describer_with_a_required_parameter(): void
    {
        $this->analyse([self::FIXTURES . 'DescribedByRequiredParameter.php'], [
            [
                'The describer method ' . self::NAMESPACE . 'DescribedByRequiredParameter::description() is called with no arguments, so its parameter $prefix must be optional.',
                9,
            ],
        ]);
    }

    public function test_it_reports_a_describer_name_that_is_not_a_literal_string(): void
    {
        $this->analyse([self::FIXTURES . 'DescribedByNonLiteral.php'], [
            [
                'The #[CasesDescribedBy] on enum ' . self::NAMESPACE . 'DescribedByNonLiteral must name its describer method with a literal string, so it can be checked.',
                9,
            ],
        ]);
    }

    protected function getRule(): Rule
    {
        return new EnumCasesDescribedByRule();
    }
}
