<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Gateway\MasterPageMatcherRulesGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;
use RulerZ\RulerZ;

class MasterService implements Target
{
    /**
     * @var MasterPageMatcherRulesGateway
     */
    private $rulesGateway;

    /**
     * @var RulerZ
     */
    private $rulerz;

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
        'search',
        'error',
    ];

    public function __construct(MasterPageMatcherRulesGateway $rulesGateway, RulerZ $rulerz)
    {
        $this->rulesGateway = $rulesGateway;
        $this->rulerz = $rulerz;
    }

    public function matchNodeId(PageMatcherContext $context)
    {
        $rules = $this->rulesGateway->get();


        // The following if statements actually check the *type* of the master page.
        // Master pages always render a page for a single product/category/content…, and thus there will be a contentId.

        if ($context->productId !== null) {
            return $this->pickNode($rules->rules['product'] ?? null, $context->productId, $context->entity);
        }

        if ($context->categoryId !== null) {
            return $this->pickNode($rules->rules['category'] ?? null, $context->categoryId, $context->entity);
        }

        if ($context->contentId !== null) {
            return $this->pickNode($rules->rules['content'] ?? null, $context->contentId, $context->entity);
        }

        // This means we are not on a entity-master page
        foreach ($this->validContexts as $contextAttribute) {
            $ruleName = $this->propertyToRuleName($contextAttribute);
            if ($context->$contextAttribute !== null) {
                if (!isset($rules->rules[$ruleName])) {
                    throw new \OutOfBoundsException('No page defined for ' . $ruleName . ' yet.');
                }

                return $this->pickNode($rules->rules[$ruleName], null, null);
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

    private function pickNode(array $rules, $itemId, $entity): string
    {
        if (isset($rules['byId'])) {
            foreach ($rules['byId'] as $rule) {
                if ($rule['itemId'] === $itemId) {
                    return $rule['nodeId'];
                }
            }
        }
        if (isset($rules['byCriterion'])) {
            foreach ($rules['byCriterion'] as $rule) {
                try {
                    if ($this->rulerz->satisfies($entity, $rule['criterion'])) {
                        return $rule['nodeId'];
                    }
                } catch (\Exception $exception) {
                    // Silently ignore errors in the rule. If a rule can not be checked it makes more sense to ignore
                    // the rule than to report an error on every master page.
                }
            }
        }
        return $rules['default'];
    }

    private function fill(MasterPageMatcherRules $rules, array $update): void
    {
        $pageType = $update['pageType'];

        // If there is an old rule, remove it. The new rule will be added again.
        $rules->rules[$pageType]['byId'] = array_values(
            array_filter(
                $rules->rules[$pageType]['byId'] ?? [],
                function ($rule) use ($update) {
                    return $rule['nodeId'] !== $update['rule']['nodeId'];
                }
            )
        );
        $rules->rules[$pageType]['byCriterion'] = array_values(
            array_filter(
                $rules->rules[$pageType]['byCriterion'] ?? [],
                function ($rule) use ($update) {
                    return $rule['nodeId'] !== $update['rule']['nodeId'];
                }
            )
        );

        if ($update['deleted']) {
            // Nothing to do since the rule was removed, above
        } elseif (isset($update['rule']['itemId'])) {
            $rules->rules[$pageType]['byId'][] = [
                'nodeId' => $update['rule']['nodeId'],
                'itemId' => $update['rule']['itemId'],
            ];
        } elseif (isset($update['rule']['criterion'])) {
            $rules->rules[$pageType]['byCriterion'][] = [
                'nodeId' => $update['rule']['nodeId'],
                'criterion' => $update['rule']['criterion'],
            ];
        } else {
            $rules->rules[$pageType]['default'] = $update['rule']['nodeId'];
        }

        $rules->sequence = $update['sequence'];
        $rules->metaData = $update['metaData'];
    }

    private function propertyToRuleName(string $name, string $separator = '_')
    {
        return strtolower(preg_replace('((?<=[A-Za-z])(?=[A-Z]))', $separator . '$1', $name));
    }
}
