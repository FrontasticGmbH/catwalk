<?php

namespace Frontastic\Catwalk\FrontendBundle\Security;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\AccountService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AccountProvider implements UserProviderInterface
{
    /** @var AccountService */
    private $accountService;

    /** @var ContextService */
    private $contextService;

    public function __construct(AccountService $accountService, ContextService $contextService)
    {
        $this->accountService = $accountService;
        $this->contextService = $contextService;
    }

    public function loadUserByUsername($username): Account
    {
        throw new \BadMethodCallException();
    }

    public function refreshUser(UserInterface $user): Account
    {
        if (!$user instanceof Account) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $context = $this->contextService->createContextFromRequest();

        return $this->accountService->refresh($user, $context->locale);
    }

    public function supportsClass($class): bool
    {
        return $class === Account::class;
    }
}
