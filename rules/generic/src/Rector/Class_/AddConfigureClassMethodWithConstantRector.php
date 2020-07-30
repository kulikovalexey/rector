<?php

declare(strict_types=1);

namespace Rector\Generic\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use Rector\Core\Util\StaticRectorStrings;

/**
 * @see \Rector\Generic\Tests\Rector\Class_\AddConfigureClassMethodWithConstantRector\AddConfigureClassMethodWithConstantRectorTest
 */
final class AddConfigureClassMethodWithConstantRector extends AbstractRector
{
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Add configure() class method with constant isset to property', [
            new CodeSample(
                <<<'PHP'
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;

class SomeClass implements ConfigurableRectorInterface
{
    private $someTypes;
    public const SOME_TYPES = '$someTypes';
}
PHP
,
                <<<'PHP'
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;

class SomeClass implements ConfigurableRectorInterface
{
    private $someTypes;
    public const SOME_TYPES = '$someTypes';

    public function configure(array $configuration): void
    {
        $this->someTypes = $configuration[self::SOME_TYPES] ?? [];
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectType($node, ConfigurableRectorInterface::class)) {
            return null;
        }

        // already added
        if ($node->getMethod('configure') !== null) {
            return null;
        }

        $constantNameToPropertyName = [];
        foreach ($node->getConstants() as $classConst) {
            if (! $classConst->isPublic()) {
                continue;
            }

            $constantName = $this->getName($classConst);
            $constantNameToPropertyName[$constantName] = StaticRectorStrings::uppercaseUnderscoreToPascalCase(
                $constantName
            );
        }

        $configureClassMethod = $this->createConfigureClassMethod();

        foreach ($constantNameToPropertyName as $constantName => $propertyName) {
            $classConstFetch = new ClassConstFetch(new Identifier('self'), $constantName);
            $arrayDimFetch = new ArrayDimFetch(new Variable('configuration'), $classConstFetch);
            $coalesce = new Coalesce($arrayDimFetch, new Array_());

            $assign = new Assign(new PropertyFetch(new Variable('this'), $propertyName), $coalesce);

            $assignExpression = new Expression($assign);
            $configureClassMethod->stmts[] = $assignExpression;
        }

        // @todo etract to factory + add stmts

        $node->stmts[] = $configureClassMethod;

        return $node;
    }

    private function createConfigureClassMethod(): ClassMethod
    {
        $configureClassMethod = $this->nodeFactory->createPublicMethod('configure');
        $configureClassMethod->returnType = new Identifier('void');
        $configurationVariable = new Variable('configuration');
        $param = new Param($configurationVariable);
        $param->type = new Identifier('array');
        $configureClassMethod->params[] = $param;
        return $configureClassMethod;
    }
}
