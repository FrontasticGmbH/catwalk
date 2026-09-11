<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Controller\ValueResolver;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ContextValueResolverTest extends TestCase
{
    /**
     * @var ContextService
     */
    private $contextServiceMock;

    /**
     * @var ContextValueResolver
     */
    private $resolver;

    public function setUp(): void
    {
        $this->contextServiceMock = \Phake::mock(ContextService::class);
        $this->resolver = new ContextValueResolver($this->contextServiceMock);
    }

    public function testIgnoresArgumentsOfOtherTypes()
    {
        $this->assertSame(
            [],
            $this->resolver->resolve(new Request(), $this->createArgument('context', Request::class))
        );
        \Phake::verifyNoInteraction($this->contextServiceMock);
    }

    public function testIgnoresUntypedArguments()
    {
        $this->assertSame(
            [],
            $this->resolver->resolve(new Request(), $this->createArgument('context', null))
        );
        \Phake::verifyNoInteraction($this->contextServiceMock);
    }

    public function testBuildsContextFromRequest()
    {
        $request = new Request();
        $context = new Context();
        \Phake::when($this->contextServiceMock)->createContextFromRequest($request)->thenReturn($context);

        $this->assertSame(
            [$context],
            $this->resolver->resolve($request, $this->createArgument('context', Context::class))
        );
    }

    public function testResolvesNullableArgumentsLikeRequiredOnes()
    {
        // ArgumentMetadata::getType() returns the bare class name for `?Context`,
        // only the isNullable flag differs.
        $request = new Request();
        $context = new Context();
        \Phake::when($this->contextServiceMock)->createContextFromRequest($request)->thenReturn($context);

        $this->assertSame(
            [$context],
            $this->resolver->resolve($request, $this->createArgument('context', Context::class, true))
        );
    }

    public function testReusesContextAlreadyPresentUnderTheArgumentName()
    {
        $existing = new Context();
        $request = new Request([], [], ['context' => $existing]);

        $this->assertSame(
            [$existing],
            $this->resolver->resolve($request, $this->createArgument('context', Context::class))
        );
        \Phake::verifyNoInteraction($this->contextServiceMock);
    }

    public function testIgnoresNonContextValueUnderTheArgumentName()
    {
        $request = new Request([], [], ['context' => 'not-a-context']);
        $fresh = new Context();
        \Phake::when($this->contextServiceMock)->createContextFromRequest($request)->thenReturn($fresh);

        $this->assertSame(
            [$fresh],
            $this->resolver->resolve($request, $this->createArgument('context', Context::class))
        );
    }

    private function createArgument(string $name, ?string $type, bool $isNullable = false): ArgumentMetadata
    {
        return new ArgumentMetadata($name, $type, false, false, null, $isNullable);
    }
}
