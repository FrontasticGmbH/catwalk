<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\ReplicatorBundle\Domain\Target;

use Frontastic\Catwalk\FrontendBundle\Gateway\NodeGateway;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\NullCache;

class NodeService implements Target
{
    /**
     * @var NodeGateway
     */
    private $nodeGateway;

    /**
     * @var PageService
     */
    private $pageService;

    /**
     * @var ContextService
     */
    private $contextService;

    /**
     * @var RouteService
     */
    private $routeService;

    private array $cache;

    private SchemaService $schemaService;

    public function __construct(
        NodeGateway $nodeGateway,
        PageService $pageService,
        RouteService $routeService,
        SchemaService $schemaService,
        ContextService $contextService
    ) {
        $this->nodeGateway = $nodeGateway;
        $this->pageService = $pageService;
        $this->routeService = $routeService;
        $this->schemaService = $schemaService;
        $this->contextService = $contextService;
        $this->cache = [];
    }

    public function lastUpdate(): string
    {
        return $this->nodeGateway->getHighestSequence();
    }

    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            try {
                $node = $this->nodeGateway->getEvenIfDeleted($update['nodeId']);
            } catch (\OutOfBoundsException $e) {
                $node = new Node();
                $node->nodeId = $update['nodeId'];
            }

            $node = $this->fill($node, $update);
            $this->store($node);
        }

        // Note: When adding node schema completion, pay attention to use gateway here!
        $this->routeService->rebuildRoutes($this->getNodes());
    }

    public function fill(Node $node, array $data): Node
    {
        // FIXME: HACK to fix incorrect path from backstage. See #324
        $data['path'] = array_filter($data['path']);

        $node->sequence = $data['sequence'];
        $node->isMaster = (bool)$data['isMaster'];
        $node->configuration = $data['configuration'];
        $node->streams = $data['streams'];
        $node->name = $data['name'];
        $node->path = '/' . implode('/', $data['path']);
        $node->depth = count($data['path']);
        $node->sort = $data['sort'];
        $node->metaData = $data['metaData'];
        $node->isDeleted = (bool)$data['isDeleted'];

        return $node;
    }

    /**
     * @return \Frontastic\Catwalk\FrontendBundle\Domain\Node[]
     */
    public function getNodes(string $root = null, int $maxDepth = null): array
    {
        return $this->nodeGateway->getTree($root, $maxDepth);
    }

    /**
     * Returns a (cloned) tree of nodes.
     *
     * Note that changes to the returned nodes are *not* reflected to other instances of the same node. We need to
     * reflect that multiple trees with different depth might be fetched in the same request.
     */
    public function getTree(string $root = null, int $maxDepth = null): Node
    {
        $nodes = $this->nodeGateway->getTree($root, $maxDepth);

        $nodeIndex = [$root => new Node()];
        foreach ($nodes as $node) {
            // Minimize potential large tree data structures
            $nodeIndex[$node->nodeId] = new Node([
                'nodeId' => $node->nodeId,
                'configuration' => $node->configuration,
                'name' => $node->name,
                'path' => $node->path,
                'depth' => $node->depth,
                // Disabled the live page feature for now since it causes major performance issues when there are a lot
                // of references on a page.
                //'hasLivePage' => $this->pageExists($node->nodeId),
            ]);
        }

        foreach ($nodeIndex as $node) {
            if (!$node->nodeId) {
                // Skip virtual root node
                continue;
            }

            $nodePath = array_filter(explode('/', $node->path));
            $parentNodeId = end($nodePath) ?: null;

            if (isset($nodeIndex[$parentNodeId])) {
                $nodeIndex[$parentNodeId]->children[] = $node;
            }
        }

        return $nodeIndex[$root];
    }

    private function cloneAll(array $objects): array
    {
        return array_map(function ($object) {
            return clone $object;
        }, $objects);
    }

    public function get(string $nodeId): Node
    {
        if (array_key_exists($nodeId, $this->cache)) {
            return $this->cache[$nodeId];
        }

        return $this->cache[$nodeId] = $this->nodeGateway->get($nodeId);
    }

    /**
     * @param array $nodeIds
     * @return Node[]
     */
    public function getByIds(array $nodeIds): array
    {
        $result = [];

        $nodeIdsToFetch = [];
        foreach ($nodeIds as $nodeId) {

            $node = array_key_exists($nodeId, $this->cache) ? $this->cache[$nodeId] : false;

            if ($node === null) {
                $nodeIdsToFetch[] = $nodeId;
            } else {
                $result[] = $node;
            }
        }

        $fetchedNodes = count($nodeIdsToFetch) > 0 ? $this->nodeGateway->getByIds($nodeIdsToFetch): [];

        foreach ($fetchedNodes as $node) {
            $this->cache[$node->nodeId] = $node;
        }

        return array_merge($result, $fetchedNodes);
    }

    public function store(Node $node): Node
    {
        return $this->nodeGateway->store($node);
    }

    public function remove(Node $node): void
    {
        $this->nodeGateway->remove($node);

        if ($node->nodeId) {
            unset($this->cache[$node->nodeId]);
        }
    }

    /**
     * TODO: Call this automatically when a node is loaded to have Frontastic React benefit from it, too!
     */
    public function completeCustomNodeData(Node $node, ?FieldVisitor $fieldVisitor = null): Node
    {
        // FIXME: Also complete data source configuration!
        $this->schemaService->completeNodeData($this->contextService->getContext(), $node, $fieldVisitor);
        return $node;
    }

    private function pageExists(string $pageFolderId)
    {
        try {
            $page = $this->pageService->fetchForNode(
                new Node(["nodeId" => $pageFolderId]),
                $this->contextService->getContext()
            );

            return $page != null;
        } catch (\Exception $e) {
            // Page does not exist, do nothing
        }

        return false;
    }
}
