<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;
use Worksome\GraphQLHelpers\Utils\UpperSnakeCaseConverter;

/**
 * Reports enum cases that would share a GraphQL value name, from a `#[GraphQLName]` override or the converted
 * case name. `PhpEnumType` throws for these when the type is built; this catches them for every enum at analysis
 * time, including enums that are only converted with `GraphQLConvertable`.
 *
 * @implements Rule<Enum_>
 */
class UniqueEnumGraphQLNamesRule implements Rule
{
    public function getNodeType(): string
    {
        return Enum_::class;
    }

    /**
     * @param Enum_ $node
     *
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $enumName = $node->namespacedName?->toString() ?? $node->name?->toString() ?? 'enum';

        /** @var array<string, string> $seen GraphQL name => the case that first used it */
        $seen = [];
        $errors = [];

        foreach ($node->stmts as $statement) {
            if (! $statement instanceof EnumCase) {
                continue;
            }

            $caseName = $statement->name->toString();
            $graphQLName = $this->graphQLName($statement) ?? UpperSnakeCaseConverter::convert($caseName);

            if (isset($seen[$graphQLName])) {
                $errors[] = RuleErrorBuilder::message(sprintf(
                    'Enum case %s::%s has the GraphQL name [%s], which case %s already uses.',
                    $enumName,
                    $caseName,
                    $graphQLName,
                    $seen[$graphQLName],
                ))
                    ->identifier('worksome.uniqueEnumGraphQLNames')
                    ->line($statement->name->getStartLine())
                    ->build();

                continue;
            }

            $seen[$graphQLName] = $caseName;
        }

        return $errors;
    }

    /** The `#[GraphQLName]` override on a case, when it is given as a literal string. */
    private function graphQLName(EnumCase $case): string|null
    {
        foreach ($case->attrGroups as $group) {
            foreach ($group->attrs as $attribute) {
                if ($attribute->name->toString() !== GraphQLName::class) {
                    continue;
                }

                $value = $attribute->args[0]->value ?? null;

                return $value instanceof String_ ? $value->value : null;
            }
        }

        return null;
    }
}
