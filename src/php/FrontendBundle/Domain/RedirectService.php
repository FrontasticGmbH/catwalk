<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

use Frontastic\Catwalk\FrontendBundle\Gateway\RedirectGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;

class RedirectService implements Target
{
    public const REDIRECT_COUNT_PARAMETER_KEY = '_frc';

    private const MAXIMUM_REDIRECT_COUNT = 3;

    /**
     * @var RedirectGateway
     */
    private $redirectGateway;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Context
     */
    private $context;

    public function __construct(RedirectGateway $redirectGateway, Router $router, Context $context)
    {
        $this->redirectGateway = $redirectGateway;
        $this->router = $router;
        $this->context = $context;
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

        $targetUrl = null;
        switch ($redirect->targetType) {
            case Redirect::TARGET_TYPE_LINK:
                $targetUrl = $redirect->target;
                break;
            case Redirect::TARGET_TYPE_NODE:
                $locales = [
                    $redirect->language,
                    $this->context->locale,
                    $this->getLanguageFromLocaleWithTerritory($this->context->locale),
                    $this->context->project->defaultLanguage,
                    $this->getLanguageFromLocaleWithTerritory($this->context->project->defaultLanguage),
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
        $additionalParameters->remove(self::REDIRECT_COUNT_PARAMETER_KEY);

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
        $redirect->language = $data['language'] ?? null;
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

    private function getLanguageFromLocaleWithTerritory(string $locale): ?string
    {
        $localeParts = explode('_', $locale);
        if (count($localeParts) === 2) {
            return $localeParts[0];
        }

        return null;
    }
}
