<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Gateway\PageGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;
use RulerZ\RulerZ;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
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

    /**
     * @var TrackingService
     */
    private $trackingService;

    private array $pageCandidatesFetched;

    public function __construct(PageGateway $pageGateway, RulerZ $rulerz, TrackingService $trackingService)
    {
        $this->pageGateway = $pageGateway;
        $this->rulerz = $rulerz;
        $this->trackingService = $trackingService;
        $this->pageCandidatesFetched = [];
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
        $page->scheduledFromTimestamp = $this->dateTimeStringToUnixTimestamp($data['scheduledFrom'] ?? null);
        $page->scheduledToTimestamp = $this->dateTimeStringToUnixTimestamp($data['scheduledTo'] ?? null);
        $page->nodesPagesOfTypeSortIndex = $data['nodesPagesOfTypeSortIndex'] ?? null;
        $page->scheduleCriterion = $data['scheduleCriterion'] ?? '';
        $page->scheduledExperiment = $data['scheduledExperiment'] ?? null;

        return $page;
    }

    public function fetchForNode(Node $node, Context $context): Page
    {
        $criterionTarget = [
            'locale' => $context->locale,
            'host' => $context->host,
        ];

        $pageCandidates = $this->getPageCandidatesForNode($node->nodeId);

        foreach ($pageCandidates as $pageCandidate) {
            try {
                $scheduled = empty($pageCandidate->scheduledExperiment) ||
                    $this->trackingService->shouldRunExperiment($pageCandidate->scheduledExperiment);
                $satisfied = empty($pageCandidate->scheduleCriterion) ||
                    $this->rulerz->satisfies($criterionTarget, $pageCandidate->scheduleCriterion);

                if ($scheduled && $satisfied) {
                    return $pageCandidate;
                }
            } catch (\Throwable $exception) {
                // Silently ignore errors in the rule. If a rule can not be
                // checked it makes more sense to ignore the rule than to
                // report an error.
            }
        }

        throw new \RuntimeException('No active page for node ' . $node->nodeId . ' found.');
    }

    /**
     * @return Page[]
     */
    private function getPageCandidatesForNode(string $nodeId): array
    {
        if (array_key_exists($nodeId, $this->pageCandidatesFetched)) {
            return $this->pageCandidatesFetched[$nodeId];
        }

        $this->pageCandidatesFetched[$nodeId] = $this->pageGateway->fetchForNode($nodeId);

        return $this->pageCandidatesFetched[$nodeId];
    }

    /**
     * Removes data from the page that would be hidden, therefore it makes no sense to return it for visualization
     * purposes.
     *
     * Data being removed:
     *  - Regions, elements and tastics with hidden desktop, mobile and tablet
     *
     * @param Page $page
     * @return Page without the unneeded data
     */
    public function filterOutHiddenData(Page $page): Page
    {
        $result = clone $page;
        $result->regions = $this->filterOutHiddenRegions($result->regions);
        return $result;
    }

    private function filterOutHiddenRegions(array $regions): array
    {
        $result = [];
        foreach ($regions as $regionIdentifier => $region) {
            if (!$region->configuration->isHidden()) {
                $result[$regionIdentifier] = $region;
                $region->elements = $this->filterOutHiddenRegionElements($region->elements);
            }
        }

        return $result;
    }

    private function filterOutHiddenRegionElements(array $regionElements): array
    {
        $result = [];
        foreach ($regionElements as $elem) {
            if (!$elem->configuration->isHidden()) {
                $result[] = $elem;
                $elem->tastics = $this->filterOutHiddenTastics($elem->tastics);
            }
        }
        return $result;
    }

    private function filterOutHiddenTastics(array $tastics): array
    {
        $result = [];
        foreach ($tastics as $tastic) {
            if (!$tastic->configuration->isHidden()) {
                $result[] = $tastic;
            }
        }
        return $result;
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
