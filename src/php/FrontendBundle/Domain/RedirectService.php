<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Gateway\RedirectGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Routing\Router;

class RedirectService implements Target
{
    public const REDIRECT_COUNT_PARAMETER_KEY = 'frontastic_redirect_counter';

    private const MAXIMUM_REDIRECT_COUNT = 3;

    /**
     * @var RedirectGateway
     */
    private $redirectGateway;

    /**
     * @var Router
     */
    private $router;

    public function __construct(RedirectGateway $redirectGateway, Router $router)
    {
        $this->redirectGateway = $redirectGateway;
        $this->router = $router;
    }

    public function lastUpdate(): string
    {
        return $this->redirectGateway->getHighestSequence();
    }

    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            try {
                $redirect = $this->redirectGateway->getEvenIfDeleted($update['redirectId']);
            } catch (\OutOfBoundsException $exception) {
                $redirect = new Redirect([
                    'redirectId' => $update['redirectId'],
                ]);
            }

            $redirect = $this->fill($redirect, $update);
            $this->redirectGateway->store($redirect);
        }
    }

    public function get(string $redirectId): Redirect
    {
        return $this->redirectGateway->get($redirectId);
    }

    public function getRedirectUrlForRequest(string $path, ParameterBag $queryParameters): ?string
    {
        $redirectCount = intval($queryParameters->get(self::REDIRECT_COUNT_PARAMETER_KEY, 0));
        if ($redirectCount > self::MAXIMUM_REDIRECT_COUNT) {
            return null;
        }

        $redirect = $this->getRedirectForRequest($path, $queryParameters);
        if ($redirect === null) {
            return null;
        }

        switch ($redirect->targetType) {
            case Redirect::TARGET_TYPE_LINK:
                $targetUrl = $redirect->target;
                break;
            case Redirect::TARGET_TYPE_NODE:
                $targetUrl = $this->router->generate('node_' . $redirect->target);
                break;
            default:
                throw new \InvalidArgumentException("Unknown redirect target type $redirect->targetType");
        }

        $additionalParameters = new ParameterBag(
            array_diff_key($queryParameters->all(), $redirect->getQueryParameters()->all())
        );

        return $this->appendQueryParametersToTargetUrl($targetUrl, $additionalParameters, $redirectCount + 1);
    }

    /**
     * @return Redirect[]
     */
    public function getRedirects(): array
    {
        return $this->redirectGateway->getAll();
    }

    protected function getRedirectForRequest(string $path, ParameterBag $queryParameters): ?Redirect
    {
        $redirects = $this->redirectGateway->getByPath($path);

        $redirects = array_filter(
            $redirects,
            function (Redirect $redirect) use ($queryParameters, &$redirectQueryCount): bool {
                foreach ($redirect->getQueryParameters()->all() as $key => $value) {
                    if (!$queryParameters->has($key) || $queryParameters->get($key) !== $value) {
                        return false;
                    }
                }

                return true;
            }
        );

        if (empty($redirects)) {
            return null;
        }

        usort(
            $redirects,
            function (Redirect $a, Redirect $b) use ($redirectQueryCount): int {
                $aQueryCount = $a->getQueryParameters()->count();
                $bQueryCount = $b->getQueryParameters()->count();

                if ($aQueryCount !== $bQueryCount) {
                    return $bQueryCount - $aQueryCount;
                }

                return strcmp($a->sequence, $b->sequence);
            }
        );

        return reset($redirects);
    }

    protected function fill(Redirect $redirect, array $data): Redirect
    {
        $redirect->sequence = $data['sequence'];
        $redirect->path = $data['path'];
        $redirect->query = $data['query'];
        $redirect->targetType = $data['target']['targetType'];
        $redirect->target = $data['target']['target'];
        $redirect->metaData = $data['metaData'];
        $redirect->isDeleted = (bool)$data['isDeleted'];

        return $redirect;
    }

    protected function appendQueryParametersToTargetUrl(
        string $targetUrl,
        ParameterBag $additionalParameters,
        int $redirectCount
    ): string {
        $urlComponents = parse_url($targetUrl);
        if ($urlComponents === false) {
            throw new \InvalidArgumentException('The url ' . $targetUrl . ' is ill formed');
        }

        $url = '';
        if (isset($urlComponents['scheme'])) {
            $url .= $urlComponents['scheme'] . '://';
        }
        if (isset($urlComponents['user']) || isset($urlComponents['pass'])) {
            $url .= ($urlComponents['user'] ?? '') . ':' . ($urlComponents['pass'] ?? '');
        }
        $url .= $urlComponents['host'] ?? '';
        if (isset($urlComponents['port'])) {
            $url .= ':' . $urlComponents['port'];
        }
        $url .= $urlComponents['path'] ?? '';
        $url .= '?';
        if (isset($urlComponents['query'])) {
            $url .= $urlComponents['query'] . '&';
        }
        if ($additionalParameters->count() > 0) {
            $url .= http_build_query($additionalParameters->all()) . '&';
        }
        $url .= self::REDIRECT_COUNT_PARAMETER_KEY . '=' . $redirectCount;
        if (isset($urlComponents['fragment'])) {
            $url .= '#' . $urlComponents['fragment'];
        }
        return $url;
    }
}
