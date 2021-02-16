<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\RemoteDecorators;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksCallBuilder;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\AccountApi;
use Frontastic\Common\AccountApiBundle\Domain\AccountApi\LifecycleEventDecorator\BaseImplementationV2;
use Frontastic\Common\AccountApiBundle\Domain\Address;
use Frontastic\Common\AccountApiBundle\Domain\PasswordResetToken;
use Frontastic\Common\CartApiBundle\Domain\Cart;

class AccountDecorator extends BaseImplementationV2
{
    use DecoratorCallTrait;

    public function beforeGetSalutations(AccountApi $accountApi, string $locale): ?array
    {
        return $this->callExpectList(HooksCallBuilder::ACCOUNT_BEFORE_GET_SALUTATIONS, [$locale]);
    }

    public function afterGetSalutations(AccountApi $accountApi, ?array $salutations): ?array
    {
        return $this->callExpectMultipleObjects(HooksCallBuilder::ACCOUNT_AFTER_GET_SALUTATIONS, [$salutations]);
    }

    public function beforeConfirmEmail(AccountApi $accountApi, string $token, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::ACCOUNT_BEFORE_CONFIRM_EMAIL, [$token, $locale]);
    }

    public function afterConfirmEmail(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_CONFIRM_EMAIL, [$account]);
    }

    public function beforeCreate(
        AccountApi $accountApi,
        Account $account,
        ?Cart $cart = null,
        string $locale = null
    ): ?array {
        return $this->callExpectList(HooksCallBuilder::ACCOUNT_BEFORE_CREATE, [$account, $cart, $locale]);
    }

    public function afterCreate(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_CREATE, [$account]);
    }

    public function beforeUpdate(AccountApi $accountApi, Account $account, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::ACCOUNT_BEFORE_UPDATE, [$account, $locale]);
    }

    public function afterUpdate(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_UPDATE, [$account]);
    }

    public function beforeUpdatePassword(
        AccountApi $accountApi,
        Account $account,
        string $oldPassword,
        string $newPassword,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::ACCOUNT_BEFORE_UPDATE_PASSWORD,
            [$account, $oldPassword, $newPassword, $locale]
        );
    }

    public function afterUpdatePassword(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_UPDATE_PASSWORD, [$account]);
    }

    public function beforeGeneratePasswordResetToken(AccountApi $accountApi, string $email): ?array
    {
        return $this->callExpectList(HooksCallBuilder::ACCOUNT_BEFORE_GENERATE_PASSWORD_RESET_TOKEN, [$email]);
    }

    public function afterGeneratePasswordResetToken(
        AccountApi $accountApi,
        PasswordResetToken $token
    ): ?PasswordResetToken {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_GENERATE_PASSWORD_RESET_TOKEN, [$token]);
    }

    public function beforeResetPassword(
        AccountApi $accountApi,
        string $token,
        string $newPassword,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::ACCOUNT_BEFORE_RESET_PASSWORD,
            [$token, $newPassword, $locale]
        );
    }

    public function afterResetPassword(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_RESET_PASSWORD, [$account]);
    }

    public function beforeLogin(
        AccountApi $accountApi,
        Account $account,
        ?Cart $cart = null,
        string $locale = null
    ): ?array {
        return $this->callExpectList(HooksCallBuilder::ACCOUNT_BEFORE_LOGIN, [$account, $cart, $locale]);
    }

    public function afterLogin(AccountApi $accountApi, ?Account $account = null): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_LOGIN, [$account]);
    }

    public function beforeRefreshAccount(AccountApi $accountApi, Account $account, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::ACCOUNT_BEFORE_REFRESH_ACCOUNT, [$account, $locale]);
    }

    public function afterRefreshAccount(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_REFRESH_ACCOUNT, [$account]);
    }

    public function beforeGetAddresses(AccountApi $accountApi, Account $account, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::ACCOUNT_BEFORE_GET_ADDRESSES, [$account, $locale]);
    }

    public function afterGetAddresses(AccountApi $accountApi, array $addresses): ?array
    {
        return $this->callExpectMultipleObjects(HooksCallBuilder::ACCOUNT_AFTER_GET_ADDRESSES, [$addresses]);
    }

    public function beforeAddAddress(
        AccountApi $accountApi,
        Account $account,
        Address $address,
        string $locale = null
    ): ?array {
        return $this->callExpectList(HooksCallBuilder::ACCOUNT_BEFORE_ADD_ADDRESS, [$account, $address, $locale]);
    }

    public function afterAddAddress(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_ADD_ADDRESS, [$account]);
    }

    public function beforeUpdateAddress(
        AccountApi $accountApi,
        Account $account,
        Address $address,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::ACCOUNT_BEFORE_UPDATE_ADDRESS,
            [$account, $address, $locale]
        );
    }

    public function afterUpdateAddress(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_UPDATE_ADDRESS, [$account]);
    }

    public function beforeRemoveAddress(
        AccountApi $accountApi,
        Account $account,
        string $addressId,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::ACCOUNT_BEFORE_REMOVE_ADDRESS,
            [$account, $addressId, $locale]
        );
    }

    public function afterRemoveAddress(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_REMOVE_ADDRESS, [$account]);
    }

    public function beforeSetDefaultBillingAddress(
        AccountApi $accountApi,
        Account $account,
        string $addressId,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::ACCOUNT_BEFORE_SET_DEFAULT_BILLING_ADDRESS,
            [$account, $addressId, $locale]
        );
    }

    public function afterSetDefaultBillingAddress(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_SET_DEFAULT_BILLING_ADDRESS, [$account]);
    }

    public function beforeSetDefaultShippingAddress(
        AccountApi $accountApi,
        Account $account,
        string $addressId,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::ACCOUNT_BEFORE_SET_DEFAULT_SHIPPING_ADDRESS,
            [$account, $addressId, $locale]
        );
    }

    public function afterSetDefaultShippingAddress(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->callExpectObject(HooksCallBuilder::ACCOUNT_AFTER_SET_DEFAULT_SHIPPING_ADDRESS, [$account]);
    }
}
