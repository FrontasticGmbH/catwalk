<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Gateway\RedirectGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Router;

class RedirectService implements Target
{
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

    public function getRedirectUrlForRequest(string $path, ParameterBag $queryParameters, Context $context): ?string
    {
        $redirect = $this->getRedirectForRequest($path, $queryParameters);
        if ($redirect === null) {
            return null;
        }

        $targetUrl = null;
        switch ($redirect->targetType) {
            case Redirect::TARGET_TYPE_LINK:
                $targetUrl = $redirect->target;
                break;
            case Redirect::TARGET_TYPE_NODE:
                $locales = [
                    $redirect->language,
                    $context->locale,
                    $this->getLanguageFromLocaleWithTerritory($context->locale),
                    $context->project->defaultLanguage,
                    $this->getLanguageFromLocaleWithTerritory($context->project->defaultLanguage),
                ];
                foreach ($locales as $locale) {
                    if ($locale === null) {
                        continue;
                    }

                    try {
                        $targetUrl = $this->router->generate('node_' . $redirect->target . '.' . $locale);
                        break;
                    } catch (RouteNotFoundException $e) {
                        // Ignore
                    }
                }
                break;
            default:
                throw new \InvalidArgumentException("Unknown redirect target type $redirect->targetType");
        }

        if ($targetUrl === null) {
            return null;
        }

        $additionalParameters = new ParameterBag(
            array_diff_key($queryParameters->all(), $redirect->getQueryParameters()->all())
        );

        return $this->appendQueryParametersToTargetUrl($targetUrl, $additionalParameters);
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
            function (Redirect $redirect) use ($queryParameters): bool {
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
            function (Redirect $a, Redirect $b): int {
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
        $redirect->language = $data['language'] ?? null;
        $redirect->metaData = $data['metaData'];
        $redirect->isDeleted = (bool)$data['isDeleted'];

        return $redirect;
    }

    protected function appendQueryParametersToTargetUrl(string $targetUrl, ParameterBag $additionalParameters): string
    {
        $urlComponents = parse_url($targetUrl);
        if ($urlComponents === false) {
            throw new \InvalidArgumentException('The url ' . $targetUrl . ' is ill formed');
        }

        return
            $this->getUrlSchema($urlComponents) .
            $this->getUrlLogin($urlComponents) .
            $this->getUrlHost($urlComponents) .
            $this->getUrlPort($urlComponents) .
            $this->getUrlPath($urlComponents) .
            $this->getUrlQuery($urlComponents, $additionalParameters) .
            $this->getUrlFragment($urlComponents);
    }

    private function getUrlSchema(array $urlComponents): string
    {
        if (!isset($urlComponents['scheme'])) {
            return '';
        }
        return $urlComponents['scheme'] . '://';
    }

    private function getUrlLogin(array $urlComponents): string
    {

        if (!isset($urlComponents['user']) && !isset($urlComponents['pass'])) {
            return '';
        }
        return ($urlComponents['user'] ?? '') . ':' . ($urlComponents['pass'] ?? '');
    }

    private function getUrlHost(array $urlComponents): string
    {
        return $urlComponents['host'] ?? '';
    }

    private function getUrlPort(array $urlComponents): string
    {
        if (!isset($urlComponents['port'])) {
            return '';
        }
        return ':' . $urlComponents['port'];
    }

    private function getUrlPath(array $urlComponents): string
    {
        return $urlComponents['path'] ?? '';
    }

    private function getUrlQuery(array $urlComponents, ParameterBag $additionalParameters): string
    {
        $query = '';
        if (isset($urlComponents['query'])) {
            $query .= '?' . $urlComponents['query'];
        }
        if ($additionalParameters->count() > 0) {
            $query .= ($query === '' ? '?' : '&') . http_build_query($additionalParameters->all());
        }

        return $query;
    }

    private function getUrlFragment(array $urlComponents): string
    {
        if (!isset($urlComponents['fragment'])) {
            return '';
        }
        return '#' . $urlComponents['fragment'];
    }

    private function getLanguageFromLocaleWithTerritory(string $locale): ?string
    {
        $localeParts = explode('_', $locale);
        if (count($localeParts) === 2) {
            return $localeParts[0];
        }

        return null;
    }
}
