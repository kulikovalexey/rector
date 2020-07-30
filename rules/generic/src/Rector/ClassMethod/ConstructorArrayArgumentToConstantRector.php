<?php

declare(strict_types=1);

namespace Rector\Generic\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Contract\Rector\RectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\Core\Util\StaticRectorStrings;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @see use mostly for https://github.com/rectorphp/rector/pull/3821
 *
 * @see \Rector\Generic\Tests\Rector\ClassMethod\ConstructorArrayArgumentToConstantRector\ConstructorArrayArgumentToConstantRectorTest
 */
final class ConstructorArrayArgumentToConstantRector extends AbstractRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Change mixed/scalar array __ $arguments to public local constant', [
            new CodeSample(
                <<<'PHP'
class SomeClass
{
    private $someTypes;

    public function __construct(array $someTypes)
    {
        $this->someTypes = $someTypes;
    }
}
PHP
,
                <<<'PHP'
class SomeClass
{
    /**
     * @var string
     */
    public const SOME_TYPES = '$someTypes';
    private $someTypes;
    public function __construct()
    {
    }
}
PHP

            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node, '__construct')) {
            return null;
        }

        $classLike = $node->getAttribute(AttributeKey::CLASS_NODE);
        if (! $classLike instanceof Class_) {
            return null;
        }

        if (! $this->isObjectType($classLike, RectorInterface::class)) {
            return null;
        }

        foreach ((array) $node->params as $key => $param) {
            $paramType = $this->getStaticType($param);
            if (! $paramType instanceof ArrayType) {
                continue;
            }

            if (! $paramType->getItemType() instanceof MixedType && ! $paramType->getItemType() instanceof StringType) {
                continue;
            }

            // match
            unset($node->params[$key]);

            $paramName = $this->getName($param);
            $this->removeParamAssignFromClassMethod($paramName, $node);

            // add constants
            $constantName = StaticRectorStrings::camelToConstant($paramName);
            $classConst = $this->nodeFactory->createPublicClassConst($constantName, '$' . $paramName);

            $this->addInterface($classLike);

            $this->addConstantToClass($classLike, $classConst);
        }

        $this->removeClassMethodIfEmpty($node);

        return $node;
    }

    private function removeParamAssignFromClassMethod(string $paramName, ClassMethod $classMethod): void
    {
        // remove property assign
        foreach ((array) $classMethod->stmts as $key => $stmt) {
            if ($stmt instanceof Expression) {
                $stmtExpr = $stmt->expr;
                if (! $stmtExpr instanceof Assign) {
                    continue;
                }
                if (! $this->isName($stmtExpr->expr, $paramName)) {
                    continue;
                }

                unset($classMethod->stmts[$key]);
            }
        }
    }

    private function addInterface(Class_ $class): void
    {
        $class->implements[] = new FullyQualified(ConfigurableRectorInterface::class);
        $class->implements = array_unique($class->implements);
    }

    private function removeClassMethodIfEmpty(ClassMethod $classMethod)
    {
        if (count($classMethod->params) > 0) {
            return;
        }

        if (count($classMethod->stmts) > 0) {
            return;
        }

        $this->removeNode($classMethod);
    }
}
