<?php

namespace Frontastic\Catwalk\FrontendBundle\Security;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\AccountService;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\CartApiBundle\Domain\CartApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) FIXME: Introcued by hotfix
 */
class Authenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    private $accountService;

    private $cartApi;

    private $contextService;

    public function __construct(AccountService $accountService, CartApi $cartApi, ContextService $contextService)
    {
        $this->accountService = $accountService;
        $this->cartApi = $cartApi;
        $this->contextService = $contextService;
    }

    /**
     * Create a passport for the current request.
     */
    public function authenticate(Request $request): Passport
    {
        $content = json_decode($request->getContent(), true);

        if (!$content) {
            throw new AuthenticationException('Invalid request content.');
        }

        $context = $this->contextService->createContextFromRequest($request);
        $locale = $context->locale;

        return new SelfValidatingPassport(
            new UserBadge(
                $content['email'],
                function (string $userIdentifier) use ($content, $locale) {
                    try {
                        $user = new Account([
                            'email' => $userIdentifier,
                        ]);
                        $user->setPassword($content['password']);

                        $account = $this->accountService->login(
                            $user,
                            $this->cartApi->getAnonymous(session_id(), $locale),
                            $locale
                        );

                        if (!$account->confirmed) {
                            throw new AuthenticationException('Not authenticated.');
                        }

                        return $account->cleanForSession();
                    } catch (AuthenticationException $e) {
                        throw $e;
                    } catch (\Exception $e) {
                        throw new AuthenticationException('Not authenticated.', 0, $e);
                    }
                }
            )
        );
    }

    /**
     * Called when authentication executed and was successful!
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new JsonResponse(new Session([
            'account' => $token->getUser(),
            'loggedIn' => true,
        ]));
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong username password).
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(
            new Session([
                'loggedIn' => false,
                'message' => $exception->getMessage() ? ('Unauthenticated: ' . $exception->getMessage()) : null,
            ]),
            ($request->get('_route') === 'Frontastic.AccountApi.Api.logout') ? 200 : 403
        );
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This is the entry point to start authentication.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse(
            new Session([
                'loggedIn' => (bool)$authException,
                'message' => $authException ? $authException->getMessage() : null,
            ]),
            $authException ? 403 : 302
        );
    }

    /**
     * Does the authenticator support the given Request?
     */
    public function supports(Request $request): ?bool
    {
        if ($request->attributes->get('_route') !== 'Frontastic.AccountApi.Api.login') {
            return false;
        }

        if (empty($request->getContent())) {
            return false;
        }

        $content = json_decode($request->getContent(), true);

        if (!$content) {
            return false;
        }

        return isset($content['email']) && isset($content['password']);
    }
}
