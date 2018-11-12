<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Gateway\MasterPageMatcherRulesGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;

class MasterService implements Target
{
    /**
     * @var MasterPageMatcherRulesGateway
     */
    private $rulesGateway;

    protected $validContexts = [
        'cart',
        'checkout',
        'checkoutFinished',
        'account',
        'accountForgotPassword',
        'accountConfirm',
        'accountProfile',
        'accountAddresses',
        'accountOrders',
        'accountWishlists',
        'accountVouchers',
        'error',
    ];

    public function __construct(MasterPageMatcherRulesGateway $rulesGateway)
    {
        $this->rulesGateway = $rulesGateway;
    }

    public function matchNodeId(PageMatcherContext $context)
    {
        $rules = $this->rulesGateway->get();

        if ($context->productId !== null) {
            return $this->pickNode($rules->rules['product'], $context->productId);
        }

        if ($context->categoryId !== null) {
            return $this->pickNode($rules->rules['category'], $context->categoryId);
        }

        foreach ($this->validContexts as $contextAttribute) {
            $ruleName = $this->propertyToRuleName($contextAttribute);
            if ($context->$contextAttribute !== null) {
                if (!isset($rules->rules[$ruleName])) {
                    throw new \OutOfBoundsException('No page defined for ' . $ruleName . ' yet.');
                }

                return $this->pickNode($rules->rules[$ruleName], null);
            }
        }

        throw new \RuntimeException('Could not resolve master page for node.');
    }

    public function completeDefaultQuery(array $streams, string $pageType, ?string $itemId): array
    {
        $pageType = $this->propertyToRuleName($pageType, '-');
        foreach ($streams as $streamKey => $streamData) {
            if (isset($streamData['configuration']) &&
                (array_key_exists($pageType, $streamData['configuration']) || $streamData['type'] === $pageType) &&
                empty($streamData['configuration'][$pageType])
            ) {
                $streams[$streamKey]['configuration'][$pageType] = $itemId;
            }
        }

        return $streams;
    }

    public function lastUpdate(): string
    {
        try {
            $latestRules = $this->rulesGateway->get();
        } catch (\OutOfBoundsException $e) {
            return '0';
        }
        return $latestRules->sequence;
    }

    public function replicate(array $updates): void
    {
        try {
            $rules = $this->rulesGateway->get();
        } catch (\OutOfBoundsException $e) {
            $rules = new MasterPageMatcherRules();
            $rules->rulesId = uniqid('generated_');
        }

        foreach ($updates as $update) {
            $this->fill($rules, $update);
        }
        $this->rulesGateway->store($rules);
    }

    private function pickNode(array $rules, $itemId): string
    {
        if (isset($rules['rules'])) {
            foreach ($rules['rules'] as $rule) {
                if ($rule['itemId'] === $itemId) {
                    return $rule['nodeId'];
                }
            }
        }
        return $rules['default'];
    }

    private function fill(MasterPageMatcherRules $rules, array $update): void
    {
        $pageType = $update['pageType'];

        if ($update['deleted']) {
            foreach ($rules->rules[$pageType]['byId'] as $index => $rule) {
                if ($rule['nodeId'] === $update['rule']['nodeId']) {
                    unset($rules->rules[$pageType]['byId'][$index]);
                }
            }
        } else {
            if (isset($update['rule']['itemId'])) {
                $rules->rules[$pageType]['byId'][] = [
                    'nodeId' => $update['rule']['nodeId'],
                    'itemId' => $update['rule']['itemId'],
                ];
            } else {
                $rules->rules[$pageType]['default'] = $update['rule']['nodeId'];
            }
        }

        $rules->sequence = $update['sequence'];
        $rules->metaData = $update['metaData'];
    }

    private function propertyToRuleName(string $name, string $separator = '_')
    {
        return strtolower(preg_replace('((?<=[A-Za-z])(?=[A-Z]))', $separator . '$1', $name));
    }
}
