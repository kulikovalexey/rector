<?php

declare(strict_types=1);

namespace Rector\Symfony\Rector\FrameworkBundle;

use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;

/**
 * @see \Rector\Symfony\Tests\Rector\FrameworkBundle\GetToConstructorInjectionRector\GetToConstructorInjectionRectorTest
 */
final class GetToConstructorInjectionRector extends AbstractToConstructorInjectionRector implements ConfigurableRectorInterface
{
    /**
     * @var string[]
     */
    private $getMethodAwareTypes = [];

    /**
     * @var string
     */
    public const GET_METHOD_AWARE_TYPES = '$getMethodAwareTypes';

    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition(
            'Turns fetching of dependencies via `$this->get()` to constructor injection in Command and Controller in Symfony',
            [
                new CodeSample(
                    <<<'PHP'
class MyCommand extends ContainerAwareCommand
{
    public function someMethod()
    {
        // ...
        $this->get('some_service');
    }
}
PHP
                    ,
                    <<<'PHP'
class MyCommand extends Command
{
    public function __construct(SomeService $someService)
    {
        $this->someService = $someService;
    }

    public function someMethod()
    {
        $this->someService;
    }
}
PHP
                ),
            ]
        );
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isObjectTypes($node->var, $this->getMethodAwareTypes)) {
            return null;
        }

        if (! $this->isName($node->name, 'get')) {
            return null;
        }

        return $this->processMethodCallNode($node);
    }
}
