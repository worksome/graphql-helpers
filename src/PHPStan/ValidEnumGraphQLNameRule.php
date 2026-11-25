<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\PHPStan;

use GraphQL\Utils\Utils;
use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\EnumCase;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Worksome\GraphQLHelpers\Definition\Attributes\GraphQLName;

/**
 * Checks the name given to a `#[GraphQLName]` attribute on an enum case.
 *
 * The name must be a literal string, so `UniqueEnumGraphQLNamesRule` and this rule can check it, and it must be a
 * valid GraphQL enum value name. graphql-php would otherwise only reject an invalid name when it validates the
 * schema.
 *
 * @implements Rule<EnumCase>
 */
class ValidEnumGraphQLNameRule implements Rule
{
    /** GraphQL forbids these as enum values, as they would be ambiguous with the literals of the same name. */
    private const array RESERVED_VALUES = ['true', 'false', 'null'];

    public function getNodeType(): string
    {
        return EnumCase::class;
    }

    /**
     * @param EnumCase $node
     *
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $attribute = $this->graphQLNameAttribute($node);

        if ($attribute === null) {
            return [];
        }

        $caseName = $node->name->toString();
        $value = $attribute->args[0]->value ?? null;

        if (! $value instanceof String_) {
            return [
                RuleErrorBuilder::message(sprintf(
                    'The #[GraphQLName] on enum case %s must be given a literal string, so it can be checked.',
                    $caseName,
                ))
                    ->identifier('worksome.literalEnumGraphQLName')
                    ->line($attribute->getStartLine())
                    ->build(),
            ];
        }

        $error = $this->invalidNameReason($value->value);

        if ($error === null) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'The #[GraphQLName] on enum case %s is not a valid GraphQL enum value name: %s',
                $caseName,
                $error,
            ))
                ->identifier('worksome.validEnumGraphQLName')
                ->line($attribute->getStartLine())
                ->build(),
        ];
    }

    private function graphQLNameAttribute(EnumCase $case): Attribute|null
    {
        foreach ($case->attrGroups as $group) {
            foreach ($group->attrs as $attribute) {
                if ($attribute->name->toString() === GraphQLName::class) {
                    return $attribute;
                }
            }
        }

        return null;
    }

    private function invalidNameReason(string $name): string|null
    {
        $error = Utils::isValidNameError($name);

        if ($error !== null) {
            return $error->getMessage();
        }

        if (in_array($name, self::RESERVED_VALUES, true)) {
            return sprintf('"%s" is reserved and cannot be used as an enum value.', $name);
        }

        return null;
    }
}
