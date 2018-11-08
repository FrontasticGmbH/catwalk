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
        'product',
        'category',
        'cart',
        'checkout',
        'checkout_finished',
        'account',
        'account_profile',
        'account_addresses',
        'account_orders',
        'account_wishlists',
        'account_vouchers',
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

        foreach ($this->validContexts as $validContext) {
            if ($context->$validContext !== null) {
                return $this->pickNode($rules->rules[$validContext], null);
            }
        }

        throw new \RuntimeException('Could not resolve node!');
    }

    public function completeDefaultQuery(array $streams, string $pageType, string $itemId): array
    {
        foreach ($streams as $streamKey => $streamData) {
            if (array_key_exists('product', $streamData['configuration'])
                && $streamData['configuration'][$pageType] === null
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
}
