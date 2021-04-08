<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\FrontendBundle\Controller\WishlistController;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\Tastic\Configuration;
use Frontastic\Catwalk\FrontendBundle\Gateway\MasterPageMatcherRulesGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;
use RulerZ\RulerZ;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MasterService implements Target
{
    /**
     * @var MasterPageMatcherRulesGateway
     */
    private $rulesGateway;

    /**
     * @var TasticService
     */
    private $tasticService;

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

    public function __construct(
        MasterPageMatcherRulesGateway $rulesGateway,
        TasticService $tasticService,
        RulerZ $rulerz
    ) {
        $this->rulesGateway = $rulesGateway;
        $this->tasticService = $tasticService;
        $this->rulerz = $rulerz;
    }

    public function matchNodeId(PageMatcherContext $context): string
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
                    throw new NotFoundHttpException('No page defined for ' . $ruleName . ' yet.');
                }

                return $this->pickNode($rules->rules[$ruleName], null, null);
            }
        }

        throw new NotFoundHttpException('Could not resolve master page for node.');
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

    /**
     * Fixes that Backstage does not send proper __master for singleton master pages.
     *
     * This should eventually be fixed in Backstage, but requires a migration phase.
     */
    public function completeTasticStreamConfigurationWithMasterDefault(Page $page, string $pageType): void
    {
        $pageType = $this->propertyToRuleName($pageType, '-');

        $tasticDefinitionMap = $this->tasticService->getTasticsMappedByType();

        foreach ($page->regions as $region) {
            foreach ($region->elements as $element) {
                foreach ($element->tastics as $tasticInstance) {
                    if (!isset($tasticDefinitionMap[$tasticInstance->tasticType])) {
                        continue;
                    }

                    $tasticDefinition = $tasticDefinitionMap[$tasticInstance->tasticType];
                    foreach ($tasticDefinition->configurationSchema['schema'] as $schema) {
                        $tasticConfiguration = (array) $tasticInstance->configuration;

                        $tasticConfiguration = $this->completeMasterDefaultIn(
                            $tasticConfiguration,
                            $schema['fields'],
                            $pageType
                        );

                        $tasticInstance->configuration = new Configuration($tasticConfiguration);
                    }
                }
            }
        }
    }

    /**
     * Complete all stream occurrences in $actualConfiguration recursively.
     */
    private function completeMasterDefaultIn(array $actualConfiguration, array $fieldDefinitions, $pageType): array
    {
        foreach ($fieldDefinitions as $fieldDefinition) {
            if (!isset($fieldDefinition['field'])) {
                continue;
            }

            $fieldIdentifier = $fieldDefinition['field'];

            if ($fieldDefinition['type'] === 'stream' && $fieldDefinition['streamType'] === $pageType) {
                if (!isset($actualConfiguration[$fieldIdentifier])) {
                    $actualConfiguration[$fieldIdentifier] = '__master';
                }
            }

            if ($fieldDefinition['type'] === 'group') {
                foreach ($actualConfiguration[$fieldIdentifier] ?? [] as $groupIndex => $groupConfiguration) {
                    $groupConfiguration = $this->completeMasterDefaultIn(
                        $groupConfiguration,
                        $fieldDefinition['fields'],
                        $pageType
                    );
                    $actualConfiguration[$fieldIdentifier][$groupIndex] = $groupConfiguration;
                }
            }
        }
        return $actualConfiguration;
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
            // LEGACY FIX: The Backstage entity uses `deleted` instead of `isDeleted` so the
            // EnvironmentReplicationFilter works only with this fix (mapping back `isDeleted` to the `deleted` flag
            // used by this entity
            if (isset($update['isDeleted'])) {
                $update['deleted'] = $update['isDeleted'];
            }

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
                } catch (\Throwable $exception) {
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
