<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Gateway\MasterPageMatcherRulesGateway;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use RulerZ\Compiler\Compiler;
use RulerZ\RulerZ;
use RulerZ\Target\Native\Native;

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

    protected function setUp()
    {
        $this->rulesGateway = \Phake::mock(MasterPageMatcherRulesGateway::class);

        $this->masterService = new MasterService(
            $this->rulesGateway,
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
}
