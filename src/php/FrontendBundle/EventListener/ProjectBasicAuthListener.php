<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\SpecificationBundle\Domain\ConfigurationSchema;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\HttpFoundation\IpUtils;

class ProjectBasicAuthListener
{
    private const ALLOWED_ROUTES = [
        'Frontastic.Frontend.Category.all',
        'Frontastic.Frontend.Preview.view',
        'Frontastic.Frontend.Preview.store',
    ];

    private const ALLOWED_PATH_PREFIXES = [
        '/api/',
    ];

    private const USERNAME_FIELD_NAME = 'frontasticBasicAuthUsername';
    private const PASSWORD_FIELD_NAME = 'frontasticBasicAuthPassword';
    private const REALM_FIELD_NAME = 'frontasticBasicAuthRealm';
    private const IPWHITELIST_FIELD_NAME = 'frontasticBasicAuthIpWhitelist';

    /*
     * This is only used if the realm is missing from the schema. Otherwise the default from the schema will be used.
     */
    private const DEFAULT_REALM = 'Access denied';

    /**
     * @var ContextService
     */
    private $contextService;

    public function __construct(ContextService $contextService)
    {
        $this->contextService = $contextService;
    }

    public function onKernelRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isAuthenticationRequired($request)) {
            return;
        }

        $context = $this->contextService->createContextFromRequest($request);

        $configuration = ConfigurationSchema::fromSchemaAndConfiguration(
            $context->projectConfigurationSchema,
            $context->projectConfiguration
        );

        $whitelistedIps = $this->getWhitelistedIps($configuration);

        if (!empty($whitelistedIps) && IpUtils::checkIp($request->getClientIp(), $whitelistedIps)) {
            return;
        }

        $expectedUser = $this->getExpectedUser($configuration);
        if ($expectedUser === null) {
            return;
        }

        $requestUser = $this->getUserFromRequest($request);
        if ($requestUser === null) {
            $event->setResponse($this->buildUnauthorizedResponse($configuration));
            return;
        }

        // Compare the passwords even if the usernames don't match to prevent timing attacks
        $usernameMatches = hash_equals($expectedUser->getUsername(), $requestUser->getUsername());
        $passwordMatches = hash_equals($expectedUser->getPassword(), $requestUser->getPassword());
        if (!$usernameMatches || !$passwordMatches) {
            $event->setResponse($this->buildUnauthorizedResponse($configuration));
        }
    }

    private function isAuthenticationRequired(Request $request): bool
    {
        $requestRoute = $request->attributes->get('_route');
        $requestPath = $request->getRequestUri();

        foreach (self::ALLOWED_ROUTES as $allowedRoute) {
            if ($requestRoute === $allowedRoute) {
                return false;
            }
        }

        foreach (self::ALLOWED_PATH_PREFIXES as $allowedPrefix) {
            if (strpos($requestPath, $allowedPrefix) === 0) {
                return false;
            }
        }

        return true;
    }

    private function getExpectedUser(ConfigurationSchema $configuration): ?User
    {
        if (!$configuration->hasField(self::USERNAME_FIELD_NAME) ||
            !$configuration->hasField(self::PASSWORD_FIELD_NAME)) {
            return null;
        }

        $username = $configuration->getFieldValue(self::USERNAME_FIELD_NAME);
        $password = $configuration->getFieldValue(self::PASSWORD_FIELD_NAME);

        if (!is_string($username) || $username === '' || !is_string($password) || $password === '') {
            return null;
        }

        return new User($username, $password);
    }

    private function getUserFromRequest(Request $request): ?User
    {
        $username = $request->getUser();
        $password = $request->getPassword();

        if (empty($username) || empty($password)) {
            return null;
        }

        return new User($username, $password);
    }

    private function buildUnauthorizedResponse(ConfigurationSchema $configurationSchema): Response
    {
        $realm = self::DEFAULT_REALM;
        if ($configurationSchema->hasField(self::REALM_FIELD_NAME)) {
            $configurationValue = $configurationSchema->getFieldValue(self::REALM_FIELD_NAME);
            if (is_string($configurationValue) && $configurationValue !== '') {
                $realm = $configurationValue;
            }
        }

        return new Response(
            '',
            Response::HTTP_UNAUTHORIZED,
            [
                'WWW-Authenticate' => sprintf('Basic realm="%s"', $realm),
            ]
        );
    }

    private function getWhitelistedIps(ConfigurationSchema $configurationSchema): array
    {
        if ($configurationSchema->hasField(self::IPWHITELIST_FIELD_NAME)) {
            $whitelistedIpGroup = $configurationSchema->getFieldValue(self::IPWHITELIST_FIELD_NAME);

            return array_filter(
                array_map(
                    function ($whitelistedIp) {
                        if (isset($whitelistedIp['ipRange'])) {
                            return $whitelistedIp['ipRange'];
                        }
                        return false;
                    },
                    $whitelistedIpGroup
                )
            );
        }

        return [];
    }
}
