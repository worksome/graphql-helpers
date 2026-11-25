<?php

declare(strict_types=1);

namespace Worksome\GraphQLHelpers\PHPStan;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Enum_;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\VerbosityLevel;
use Worksome\GraphQLHelpers\Definition\Attributes\CasesDescribedBy;

/**
 * Checks the describer method named by a `#[CasesDescribedBy]` attribute on an enum.
 *
 * `DescribedByExtractor` calls the method on each case, with no arguments, and throws when it is missing or returns
 * something other than a string or null. That only happens when `PhpEnumType` builds the type; this reports the same
 * problems at analysis time.
 *
 * @implements Rule<InClassNode>
 */
class EnumCasesDescribedByRule implements Rule
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     *
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $enum = $node->getOriginalNode();

        if (! $enum instanceof Enum_) {
            return [];
        }

        $attribute = $this->casesDescribedByAttribute($enum);

        if ($attribute === null) {
            return [];
        }

        $classReflection = $node->getClassReflection();
        $enumName = $classReflection->getDisplayName();
        $value = $attribute->args[0]->value ?? null;

        if (! $value instanceof String_) {
            return [
                $this->error(
                    sprintf(
                        'The #[CasesDescribedBy] on enum %s must name its describer method with a literal string, so it can be checked.',
                        $enumName,
                    ),
                    $attribute,
                ),
            ];
        }

        $describer = $value->value;

        if (! $classReflection->hasNativeMethod($describer)) {
            return [
                $this->error(
                    sprintf(
                        'The describer method %s() named by #[CasesDescribedBy] does not exist on enum %s.',
                        $describer,
                        $enumName
                    ),
                    $attribute,
                ),
            ];
        }

        $errors = [];
        $method = $classReflection->getNativeMethod($describer);

        foreach ($method->getVariants() as $variant) {
            foreach ($variant->getParameters() as $parameter) {
                if (! $parameter->isOptional()) {
                    $errors[] = $this->error(
                        sprintf(
                            'The describer method %s::%s() is called with no arguments, so its parameter $%s must be optional.',
                            $enumName,
                            $describer,
                            $parameter->getName(),
                        ),
                        $attribute,
                    );
                }
            }

            $returnType = $variant->getReturnType();

            if (! TypeCombinator::addNull(new StringType())->isSuperTypeOf($returnType)->yes()) {
                $errors[] = $this->error(
                    sprintf(
                        'The describer method %s::%s() must return string or null, %s given.',
                        $enumName,
                        $describer,
                        $returnType->describe(VerbosityLevel::typeOnly()),
                    ),
                    $attribute,
                );
            }
        }

        return $errors;
    }

    private function casesDescribedByAttribute(Enum_ $enum): Attribute|null
    {
        foreach ($enum->attrGroups as $group) {
            foreach ($group->attrs as $attribute) {
                if ($attribute->name->toString() === CasesDescribedBy::class) {
                    return $attribute;
                }
            }
        }

        return null;
    }

    private function error(string $message, Attribute $attribute): IdentifierRuleError
    {
        return RuleErrorBuilder::message($message)
            ->identifier('worksome.enumCasesDescribedBy')
            ->line($attribute->getStartLine())
            ->build();
    }
}
