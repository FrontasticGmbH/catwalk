<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\ReplicatorBundle\Domain\Customer;
use Frontastic\Common\ReplicatorBundle\Domain\Project;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context\FrontasticReactLocaleResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use ArrayObject;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContextServiceTest extends TestCase
{
    /**
     * @var ContextService
     */
    private $contextService;

    /**
     * @var Router|MockObject
     */
    private $routerMock;

    /**
     * @var RequestStack|MockObject
     */
    private $requestStackMock;

    /**
     * @var CustomerService|MockObject
     */
    private $customerServiceMock;

    /**
     * @var ProjectService|MockObject
     */
    private $projectServiceMock;

    /**
     * @var TokenStorage|MockObject
     */
    private $tokenStorageMock;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session|MockObject
     */
    private $httpSessionMock;

    public function setUp(): void
    {
        $this->routerMock = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->requestStackMock = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->customerServiceMock = $this->getMockBuilder(CustomerService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->projectServiceMock = $this->getMockBuilder(ProjectService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->tokenStorageMock = $this->getMockBuilder(TokenStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->contextService = new ContextService(
            $this->routerMock,
            $this->requestStackMock,
            $this->customerServiceMock,
            $this->projectServiceMock,
            $this->tokenStorageMock,
            new FrontasticReactLocaleResolver(),
            []
        );

        $this->routerMock->expects($this->any())->method('getRouteCollection')
            ->will($this->returnValue(new ArrayObject()));

        $this->tokenStorageMock->expects($this->any())->method('getToken')
            ->will($this->returnValue(null));

        $this->httpSessionMock = new \Symfony\Component\HttpFoundation\Session\Session(
            new MockArraySessionStorage()
        );
    }

    public function testGetCurrency()
    {
        $customer = $this->thisMockCustomerAndProject();

        $context = $this->contextService->getContext('de_DE');

        $this->assertSame('EUR', $context->currency);
    }

    /**
     * Regression avoidance test
     */
    public function testGetRequestDefault()
    {
        $customer = $this->thisMockCustomerAndProject();

        $context = $this->contextService->getContext();

        $this->assertSame($customer, $context->customer);
        $this->assertSame($customer->projects[0], $context->project);

        $this->assertSame('en_GB', $context->locale);
        // FIXME: Locale not determined correctly, should be checked here

        $this->assertEquals(new Session(), $context->session);
    }

    /**
     * Regression avoidance test
     */
    public function testGetRequestOverwriteLocale()
    {
        $customer = $this->thisMockCustomerAndProject();

        $context = $this->contextService->getContext('de_DE');

        $this->assertSame('de_DE', $context->locale);
    }

    /**
     * Regression avoidance test
     */
    public function testGetRequestOverwriteSession()
    {
        $customer = $this->thisMockCustomerAndProject();

        $session = new Session();

        // FIXME: Locale should be null and fall to default language!
        $context = $this->contextService->getContext('en_GB', $session);

        $this->assertSame($session, $context->session);
    }

    /**
     * Regression avoidance test
     */
    public function testCreateContextFromRequestDefault()
    {
        $customer = $this->thisMockCustomerAndProject();

        $request = new Request();
        $request->request->set('locale', 'en_GB');
        $request->setSession($this->httpSessionMock);

        $context = $this->contextService->createContextFromRequest($request);

        $this->assertSame('en_GB', $context->locale);
    }

    /**
     * Regression avoidance test
     */
    public function testCreateContextFromRequestOverrideLocale()
    {
        $customer = $this->thisMockCustomerAndProject();

        $request = new Request();
        $request->request->set('locale', 'de_DE');
        $request->setSession($this->httpSessionMock);

        $context = $this->contextService->createContextFromRequest($request);

        $this->assertSame('de_DE', $context->locale);
    }

    private function thisMockCustomerAndProject(): Customer
    {
        $project = new Project([
            'languages' => ['en_GB', 'de_DE'],
            'defaultLanguage' => 'en_GB',
        ]);

        $customer = new Customer();
        $customer->projects = [$project];

        $this->customerServiceMock->expects($this->any())->method('getCustomer')
            ->will($this->returnValue($customer));

        $this->projectServiceMock->expects($this->any())->method('getProject')
            ->will($this->returnValue($project));

        return $customer;
    }
}
