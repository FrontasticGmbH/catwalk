<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Gateway\MasterPageMatcherRulesGateway;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use RulerZ\Compiler\Compiler;
use RulerZ\RulerZ;
use RulerZ\Target\Native\Native;
use stdClass;

class MasterServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MasterPageMatcherRulesGateway
     */
    private $rulesGateway;

    /**
     * @var MasterService
     */
    private $masterService;

    /**
     * @var TasticService
     */
    private $tasticService;

    protected function setUp(): void
    {
        $this->rulesGateway = \Phake::mock(MasterPageMatcherRulesGateway::class);
        $this->tasticService = \Phake::mock(TasticService::class);

        $this->masterService = new MasterService(
            $this->rulesGateway,
            $this->tasticService,
            new RulerZ(Compiler::create(), [new Native()])
        );
    }

    public function testMatchProductByCriterionWithoutSpaces()
    {
        $rules = new MasterPageMatcherRules();
        $rules->rules['product'] = [
            'byCriterion' => [
                [
                    'criterion' => 'version="other_version"',
                    'nodeId' => 'criterion_node_1',
                ],
            ],
            'default' => 'default_node_id',
        ];

        \Phake::when($this->rulesGateway)->get()->thenReturn($rules);

        $context = new PageMatcherContext([
            'productId' => 'my_id',
            'entity' => new Product([
                'productId' => 'my_id',
                'version' => 'my_version',
            ]),
        ]);

        $nodeId = $this->masterService->matchNodeId($context);

        $this->assertEquals('default_node_id', $nodeId);
    }

    public function testMatchProductByNotMatchingVersion()
    {
        $rules = new MasterPageMatcherRules();
        $rules->rules['product'] = [
            'byCriterion' => [
                [
                    'criterion' => 'version = "other_version"',
                    'nodeId' => 'criterion_node_1',
                ],
            ],
            'default' => 'default_node_id',
        ];

        \Phake::when($this->rulesGateway)->get()->thenReturn($rules);

        $context = new PageMatcherContext([
            'productId' => 'my_id',
            'entity' => new Product([
                'productId' => 'my_id',
                'version' => 'my_version',
            ]),
        ]);

        $nodeId = $this->masterService->matchNodeId($context);

        $this->assertEquals('default_node_id', $nodeId);
    }

    public function testMatchProductByMatchingVersion()
    {
        $rules = new MasterPageMatcherRules();
        $rules->rules['product'] = [
            'byCriterion' => [
                [
                    'criterion' => 'version = "my_version"',
                    'nodeId' => 'criterion_node_1',
                ],
            ],
            'default' => 'default_node_id',
        ];

        \Phake::when($this->rulesGateway)->get()->thenReturn($rules);

        $context = new PageMatcherContext([
            'productId' => 'my_id',
            'entity' => new Product([
                'productId' => 'my_id',
                'version' => 'my_version',
            ]),
        ]);

        $nodeId = $this->masterService->matchNodeId($context);

        $this->assertEquals('criterion_node_1', $nodeId);
    }

    public function testCompleteTasticStreamConfigWithMasterDefault()
    {
        \Phake::when($this->tasticService)->getTasticsMappedByType()->thenReturn(
            require __DIR__ . '/_fixtures/master-service-complete-stream/tastic-map.php'
        );

        /** @var Page $pageFixture */
        $pageFixture = require __DIR__ . '/_fixtures/master-service-complete-stream/page.php';

        $this->masterService->completeTasticStreamConfigurationWithMasterDefault($pageFixture, 'accountOrders');

        $this->assertEquals(
            '__master',
            $pageFixture->regions['main']->elements[0]->tastics[1]->configuration->orders
        );
    }

    public function testCompleteTasticStreamConfigWithMasterDefaultWithGroups()
    {
        \Phake::when($this->tasticService)->getTasticsMappedByType()->thenReturn(
            require __DIR__ . '/_fixtures/master-service-complete-stream/tastic-map-with-groups.php'
        );

        /** @var Page $pageFixture */
        $pageFixture = require __DIR__ . '/_fixtures/master-service-complete-stream/page-with-groups.php';

        $this->masterService->completeTasticStreamConfigurationWithMasterDefault($pageFixture, 'accountOrders');

        foreach ($pageFixture->regions['main']->elements[0]->tastics[1]->configuration->ordersGroup as $orderGroup) {
            $this->assertEquals(
                '__master',
                $orderGroup['orders']
            );
        }
    }

    /**
     * @group regression
     */
    public function testReplicationCreatesValidMasterPageMatcherRules()
    {
        $pageMatcherRules = new MasterPageMatcherRules();

        \Phake::when($this->rulesGateway)->get->thenReturn($pageMatcherRules);

        $updatesFixture = include __DIR__ . '/_fixtures/master-service-replication-regression/fixture.export.php';

        $actualStates = [clone $pageMatcherRules];
        foreach ($updatesFixture as $singleUpdateBatchFixture) {
            $this->masterService->replicate($singleUpdateBatchFixture);
            $actualStates[] = clone $pageMatcherRules;
        }

        $expectedStates = include __DIR__ . '/_fixtures/master-service-replication-regression/expected.export.php';

        $this->assertEquals(
            $expectedStates,
            $actualStates
        );
    }
}
