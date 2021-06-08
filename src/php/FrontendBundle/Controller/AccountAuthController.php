<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Assert\Assertion;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Security\Authenticator;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\AccountService;
use Frontastic\Common\AccountApiBundle\Domain\Address;
use Frontastic\Common\AccountApiBundle\Domain\DuplicateAccountException;
use Frontastic\Common\CoreBundle\Domain\ErrorResult;
use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Monolog\Logger;
use QafooLabs\MVC\RedirectRoute;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @IgnoreAnnotation("Docs\Request")
 * @IgnoreAnnotation("Docs\Response")
 */
class AccountAuthController
{
    private TrackingService $trackingService;
    private AccountService $accountService;
    private Authenticator $authenticator;
    private CartFetcher $cartFetcher;
    private GuardAuthenticatorHandler $guardAuthenticatorHandler;
    private Logger $logger;

    public function __construct(
        TrackingService $trackingService,
        AccountService $accountService,
        Authenticator $authenticator,
        CartFetcher $cartFetcher,
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        Logger $logger
    ) {
        $this->trackingService = $trackingService;
        $this->accountService = $accountService;
        $this->authenticator = $authenticator;
        $this->cartFetcher = $cartFetcher;
        $this->guardAuthenticatorHandler = $guardAuthenticatorHandler;
        $this->logger = $logger;
    }

    public function indexAction(Request $request, UserInterface $account = null): JsonResponse
    {
        Assertion::isInstanceOf($account, Account::class);

        return new JsonResponse($this->getAccountService()->getSessionFor($account));
    }

    /**
     * Register a new user
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/register",
     *  "Account{
     *      birthday: ?string,
     *      phone: ?string,
     *      phonePrefix: ?string,
     *      billingAddress: ?Address,
     *      shippingAddress: ?Address
     *  }"
     * )
     * @Docs\Response(
     *  "200",
     *  "Session"
     * )
     */
    public function registerAction(Request $request, Context $context): Response
    {
        $body = $this->getJsonBody($request);

        $account = Account::newWithProjectSpecificData(
            array_merge(
                $body,
                [
                    'birthday' => $this->parseBirthday($body)
                    /** @TODO: To guarantee BC only!
                     * This data should be mapped on the corresponding EventDecorator
                     * Remove the commented lines below if the data is already handle in MapAccountDataDecorator
                     */
                    // 'data' => [
                    // 'phonePrefix' => $body['phonePrefix'] ?? null,
                    // 'phone' => $body['phone'] ?? null,
                    // ],
                ]
            )
        );

        $account->projectSpecificData = $this->parseProjectSpecificDataByKeys($body, ['phonePrefix', 'phone']);
        $account->setPassword($body['password']);

        if (isset($body['billingAddress'])) {
            $address = Address::newWithProjectSpecificData($body['billingAddress']);
            $address->isDefaultBillingAddress = true;
            $address->isDefaultShippingAddress = !isset($body['shippingAddress']);
            $account->addresses[] = $address;
        }
        if (isset($body['shippingAddress'])) {
            $address = Address::newWithProjectSpecificData($body['shippingAddress']);
            $address->isDefaultShippingAddress = true;
            $account->addresses[] = $address;
        }

        try {
            $account = $this->getAccountService()->create(
                $account,
                $this->cartFetcher->fetchCart($context, $request),
                $context->locale
            );
        } catch (DuplicateAccountException $exception) {
            return new JsonResponse(new ErrorResult(['message' => "Die E-Mail-Adresse wird bereits verwendet."]), 409);
        }

        if ($account->confirmationToken !== null) {
            $this->getAccountService()->sendConfirmationMail($account);
        }

        $this->trackingService->reachRegistration($context, $account);

        return $this->loginAccount($account, $request);
    }

    /**
     * Confirm user by their confirmation token (from email)
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/confirm/{confirmationToken}"
     * )
     * @Docs\Response(
     *  "200",
     *  "Session"
     * )
     */
    public function confirmAction(Request $request, Context $context, string $confirmationToken): Response
    {
        $account = $this->getAccountService()->confirmEmail($confirmationToken, $context->locale);

        return $this->loginAccount($account, $request);
    }

    /**
     * Request new reset token
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/request",
     *  "object{email: string}"
     * )
     * @Docs\Response(
     *  "200",
     *  "Session"
     * )
     */
    public function requestResetAction(Request $request): RedirectRoute
    {
        $body = $this->getJsonBody($request);
        $this->getAccountService()->sendPasswordResetMail($body['email']);

        return new RedirectRoute('Frontastic.AccountBundle.Account.logout');
    }

    /**
     * Request new reset token
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/reset/{token}",
     *  "object{newPassword: string}"
     * )
     * @Docs\Response(
     *  "200",
     *  "Session"
     * )
     */
    public function resetAction(Request $request, Context $context, string $token): Response
    {
        $body = $this->getJsonBody($request);
        $account = $this->getAccountService()->resetPassword($token, $body['newPassword'], $context->locale);

        return $this->loginAccount($account, $request);
    }

    /**
     * Change password of current account
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/password",
     *  "object{oldPassword: string, newPassword: string}"
     * )
     * @Docs\Response(
     *  "201",
     *  "Account"
     * )
     */
    public function changePasswordAction(Request $request, Context $context): JsonResponse
    {
        $this->assertIsAuthenticated($context);

        $account = $context->session->account;

        $body = $this->getJsonBody($request);
        $account = $this->getAccountService()
            ->updatePassword($account, $body['oldPassword'], $body['newPassword'], $context->locale);
        return new JsonResponse($account, 201);
    }

    /**
     * Update properties in user acccount
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/update",
     *  "Account{birthday: ?string}"
     * )
     * @Docs\Response(
     *  "200",
     *  "Account"
     * )
     */
    public function updateAction(Request $request, Context $context): JsonResponse
    {
        $this->assertIsAuthenticated($context);

        $body = $this->getJsonBody($request);

        $account = $context->session->account;
        $account->updateWithProjectSpecificData(
            array_merge(
                $body,
                [
                    'birthday' => $this->parseBirthday($body),
                    /** @TODO: To guarantee BC only!
                     * This data should be mapped on the corresponding EventDecorator
                     * Remove the commented lines below if the data is already handle in MapAccountDataDecorator
                     */
                    // 'data' => [
                    // 'phonePrefix' => $body['phonePrefix'] ?? null,
                    // 'phone' => $body['phone'] ?? null,
                    // ]
                ]
            )
        );

        $account = $this->getAccountService()->update($account, $context->locale);

        return new JsonResponse($account, 200);
    }

    /**
     * @param \Frontastic\Catwalk\ApiCoreBundle\Domain\Context $context
     */
    protected function assertIsAuthenticated(Context $context): void
    {
        if (!$context->session->loggedIn) {
            throw new AuthenticationException('Not logged in.');
        }
    }

    /**
     * @return \Frontastic\Common\AccountApiBundle\Domain\AccountApi
     */
    protected function getAccountService(): AccountService
    {
        return $this->accountService;
    }

    protected function getJsonBody(Request $request): array
    {
        if (!$request->getContent() ||
            !($body = Json::decode($request->getContent(), true))) {
            throw new \InvalidArgumentException('Invalid data passed: ' . $request->getContent());
        }

        return $body;
    }

    protected function loginAccount(Context $context, Account $account, Request $request): Response
    {
        $this->trackingService->reachLogin($context, $account);

        return $this->guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
            $account,
            $request,
            $this->authenticator,
            'api'
        );
    }

    private function parseBirthday(array $body): ?\DateTimeImmutable
    {
        return isset($body['birthdayYear']) ?
            new \DateTimeImmutable(
                $body['birthdayYear'] .
                '-' . ($body['birthdayMonth'] ?? 1) .
                '-' . ($body['birthdayDay'] ?? 1) .
                'T12:00'
            ) :
            null;
    }

    private function parseProjectSpecificDataByKeys(array $requestBody, array $keys): array
    {
        $projectSpecificData = $requestBody['projectSpecificData'] ?? [];

        foreach ($keys as $key) {
            if (!key_exists($key, $projectSpecificData) && key_exists($key, $requestBody)) {
                $this->logger
                    ->warning(
                        'This usage of the key "{key}" is deprecated, move it into "projectSpecificData" instead',
                        ['key' => $key]
                    );
                $projectSpecificData['custom'][$key] = $requestBody[$key] ?? [];
            }
        }

        return $projectSpecificData;
    }
}
