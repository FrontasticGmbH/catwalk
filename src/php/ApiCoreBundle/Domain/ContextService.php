<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Route;

use Frontastic\Common\ReplicatorBundle\Domain\Customer;

class ContextService
{
    private $router;

    private $requestStack;

    private $customerService;

    public function __construct(Router $router, RequestStack $requestStack, CustomerService $customerService)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->customerService = $customerService;
    }

    public function createContextFromRequest(Request $request = null): Context
    {
        $request = $request ?: $this->requestStack->getCurrentRequest();

        // @HACK: Should be removed once we are using sensible security
        if ($request && $request->getSession()) {
            $session = $request->getSession();
            $session->start();

            if (!$session->has('userId')) {
                $session->set('userId', md5(microtime()));
            }
        }

        return $this->getContext(
            $request ? $request->get('locale', 'en_GB') : 'en_GB',
            new Session()
        );
    }

    public function getContext(string $locale = 'en_GB', Session $session = null): Context
    {
        $customer = $this->customerService->getCustomer();
        $project = reset($customer->projects);

        if (!in_array($locale, $project->languages)) {
            $locale = $project->defaultLanguage;
        }

        return new Context([
            'environment' => \Frontastic\Catwalk\AppKernel::getEnvironmentFromConfiguration(),
            'customer' => $customer,
            'project' => $project,
            'locale' => $locale,
            // @TODO: Build currency from locale
            'currency' => 'EUR',
            'session' => $session ?: new Session(),
            'routes' => array_map(
                function (Route $route): string {
                    return $route->getPath();
                },
                iterator_to_array($this->router->getRouteCollection())
            ),
        ]);
    }
}
