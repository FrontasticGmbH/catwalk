<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Gateway\PageGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;
use RulerZ\RulerZ;

class PageService implements Target
{
    /**
     * @var PageGateway
     */
    private $pageGateway;

    /**
     * @var RulerZ
     */
    private $rulerz;

    public function __construct(PageGateway $pageGateway, RulerZ $rulerz)
    {
        $this->pageGateway = $pageGateway;
        $this->rulerz = $rulerz;
    }

    public function lastUpdate(): string
    {
        return $this->pageGateway->getHighestSequence();
    }

    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            try {
                $page = $this->pageGateway->getEvenIfDeleted($update['pageId']);
            } catch (\OutOfBoundsException $e) {
                $page = new Page();
                $page->pageId = $update['pageId'];
            }

            $page = $this->fill($page, $update);
            $this->store($page);
        }
    }

    public function fill(Page $page, array $data): Page
    {
        $page->sequence = $data['sequence'];
        $page->node = $data['nodes'][0] ?? new Node();
        $page->layoutId = $data['layoutId'];
        $page->regions = array_map(
            function (array $regionData): Region {
                $region = new Region();
                $region->regionId = $regionData['regionId'];
                $region->configuration = new Region\Configuration($regionData['configuration']);
                $region->elements = array_map(
                    function (array $cellData): Cell {
                        $cell = new Cell();
                        $cell->cellId = $cellData['cellId'];
                        $cell->configuration = new Cell\Configuration($cellData['configuration']);

                        $cell->customConfiguration = (isset($cellData['customConfiguration'])
                            ? (object)$cellData['customConfiguration']
                            : null
                        );

                        $cell->tastics = array_map(
                            function (array $tasticData): Tastic {
                                $tastic = new Tastic();
                                $tastic->tasticId = $tasticData['tasticId'];
                                $tastic->tasticType = $tasticData['tasticType'];
                                $tastic->configuration = new Tastic\Configuration($tasticData['configuration']);

                                return $tastic;
                            },
                            $cellData['tastics']
                        );

                        return $cell;
                    },
                    $regionData['elements']
                );

                return $region;
            },
            $data['regions']
        );
        $page->metaData = $data['metaData'];
        $page->isDeleted = $data['isDeleted'];
        $page->state = $data['state'];
        $page->scheduledFromTimestamp = $this->dateTimeStringToUnixTimestamp($data['scheduledFrom']);
        $page->scheduledToTimestamp = $this->dateTimeStringToUnixTimestamp($data['scheduledTo']);
        $page->nodesPagesOfTypeSortIndex = $data['nodesPagesOfTypeSortIndex'];
        $page->scheduleCriterion = $data['scheduleCriterion'] ?? '';

        return $page;
    }

    public function fetchForNode(Node $node, Context $context): Page
    {
        $criterionTarget = [
            'locale' => $context->locale,
        ];

        $pageCandidates = $this->pageGateway->fetchForNode($node->nodeId);
        foreach ($pageCandidates as $pageCandidate) {
            if (empty($pageCandidate->scheduleCriterion)) {
                return $pageCandidate;
            }

            try {
                if ($this->rulerz->satisfies($criterionTarget, $pageCandidate->scheduleCriterion)) {
                    return $pageCandidate;
                }
            } catch (\Throwable $exception) {
                // Silently ignore errors in the rule. If a rule can not be checked it makes more sense to ignore
                // the rule than to report an error.
            }
        }

        throw new \RuntimeException('No active page for node ' . $node->nodeId . ' found.');
    }

    public function get(string $pageId): Page
    {
        return $this->pageGateway->get($pageId);
    }

    public function store(Page $page): Page
    {
        return $this->pageGateway->store($page);
    }

    public function remove(Page $page): void
    {
        $this->pageGateway->remove($page);
    }

    private function dateTimeStringToUnixTimestamp(string $time = null)
    {
        if ($time === null) {
            return null;
        }

        return strtotime($time);
    }
}
